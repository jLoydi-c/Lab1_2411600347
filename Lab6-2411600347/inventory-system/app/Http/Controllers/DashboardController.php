<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $lowStockCount = Product::lowStock()->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::outOfStock()->count();

        $totalValue = Product::all()->sum(function ($product) {
            return $product->quantity * $product->unit_price;
        });

        $lowStockProducts = Product::lowStock()->orderBy('quantity')->take(10)->get();
        $recentTransactions = InventoryTransaction::with(['product', 'user'])->recent()->take(10)->get();

        return view('dashboard', compact(
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalValue',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}