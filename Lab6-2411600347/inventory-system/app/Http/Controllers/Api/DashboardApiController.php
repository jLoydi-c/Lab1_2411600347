<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryTransactionResource;
use App\Http\Resources\ProductResource;
use App\Models\InventoryTransaction;
use App\Models\Product;

class DashboardApiController extends Controller
{
    // GET /api/dashboard/stats
    public function stats()
    {
        $products = Product::all();

        return response()->json([
            'total_products' => $products->count(),
            'total_value' => round($products->sum(fn ($p) => $p->quantity * $p->unit_price), 2),
            'low_stock_count' => Product::lowStock()->where('quantity', '>', 0)->count(),
            'out_of_stock_count' => Product::outOfStock()->count(),
            'unread_notifications' => auth()->user()->unreadNotifications()->count(),
            'last_updated' => now()->format('h:i:s A'),
        ]);
    }

    // GET /api/products/low-stock
    public function lowStock()
    {
        $products = Product::lowStock()->orderBy('quantity')->take(10)->get();

        return ProductResource::collection($products);
    }

    // GET /api/transactions/recent
    public function recentTransactions()
    {
        $transactions = InventoryTransaction::with(['product', 'user'])->recent()->take(10)->get();

        return InventoryTransactionResource::collection($transactions);
    }

    // GET /api/products/{product}  (used by the adjustment form)
    public function productShow(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $product->quantity,
            'reorder_level' => $product->reorder_level,
        ]);
    }
}