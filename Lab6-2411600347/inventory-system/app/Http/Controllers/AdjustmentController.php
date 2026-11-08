<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    public const REASONS = [
        'count_discrepancy' => 'Physical count discrepancy',
        'damaged' => 'Damaged goods',
        'theft_loss' => 'Theft / loss',
        'system_error' => 'System error',
    ];

    public function create()
    {
        abort_unless(auth()->user()->is_admin, 403);

        $products = Product::orderBy('name')->get();
        $reasons = self::REASONS;

        return view('adjustments.create', compact('products', 'reasons'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->is_admin, 403);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'physical_count' => 'required|integer|min:0',
            'reason' => 'required|in:' . implode(',', array_keys(self::REASONS)),
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                // difference = what we counted - what the system says
                $difference = (int) $validated['physical_count'] - $product->quantity;

                if ($difference == 0) {
                    throw new \Exception('The physical count matches the system count. No adjustment needed.');
                }

                // the observer updates the product quantity
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'adjustment',
                    'quantity' => $difference,
                    'reference_number' => 'ADJ-' . now()->format('YmdHis'),
                    'reason' => $validated['reason'],
                    'notes' => $validated['notes'] ?? null,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['physical_count' => $e->getMessage()]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Stock adjustment recorded.');
    }
}