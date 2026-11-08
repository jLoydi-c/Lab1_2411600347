<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryTransactionController extends Controller
{
    // list with filters and pagination
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['product', 'user'])->recent();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get();
        $types = InventoryTransaction::TYPES;

        return view('transactions.index', compact('transactions', 'products', 'types'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $types = [
            'stock_in' => 'Stock In',
            'stock_out' => 'Stock Out',
        ];

        return view('transactions.create', compact('products', 'types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        // after-validation hook: check there is enough stock for stock out
        $validator->after(function ($validator) use ($request) {
            if ($request->type === 'stock_out' && $request->product_id && $request->quantity) {
                $product = Product::find($request->product_id);

                if ($product && (int) $request->quantity > $product->quantity) {
                    $validator->errors()->add(
                        'quantity',
                        'Not enough stock. Only ' . $product->quantity . ' available.'
                    );
                }
            }
        });

        $validated = $validator->validate();

        $quantity = (int) $validated['quantity'];
        if ($validated['type'] === 'stock_out') {
            $quantity = -$quantity; // stock out is saved as a negative number
        }

        try {
            // if anything fails inside here, everything is rolled back
            DB::transaction(function () use ($validated, $quantity) {
                InventoryTransaction::create([
                    'product_id' => $validated['product_id'],
                    'user_id' => auth()->id(),
                    'type' => $validated['type'],
                    'quantity' => $quantity,
                    'reference_number' => $validated['reference_number'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction recorded successfully!');
    }

    public function show(InventoryTransaction $transaction)
    {
        $transaction->load(['product', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    // admin only, and it reverses the stock change
    public function destroy(InventoryTransaction $transaction)
    {
        abort_unless(auth()->user()->is_admin, 403);

        try {
            DB::transaction(function () use ($transaction) {
                $product = Product::lockForUpdate()->findOrFail($transaction->product_id);
                $newQuantity = $product->quantity - $transaction->quantity;

                if ($newQuantity < 0) {
                    throw new \Exception('Cannot delete: reversing this transaction would make stock negative.');
                }

                $product->quantity = $newQuantity;
                $product->save();
                $transaction->delete();
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted and stock reversed.');
    }
}