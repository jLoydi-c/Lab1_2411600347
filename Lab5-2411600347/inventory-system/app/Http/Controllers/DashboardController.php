<?php

namespace App\Http\Controllers;

use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        $lowStockCount = Product::whereColumn('quantity', '<=', 'reorder_level')
                                 ->where('quantity', '>', 0)
                                 ->count();

        $outOfStockCount = Product::where('quantity', 0)->count();

        $totalValue = Product::all()->sum(function ($product) {
            return $product->quantity * $product->unit_price;
        });

        $recentProducts = Product::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts', 'lowStockCount', 'outOfStockCount', 'totalValue', 'recentProducts'
        ));
    }
}