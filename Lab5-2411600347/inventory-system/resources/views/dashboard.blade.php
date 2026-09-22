@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2 class="mb-4">Welcome back, {{ auth()->user()->name }}</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Total Products</h6>
                <h3 class="stat-value">{{ $totalProducts }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Total Inventory Value</h6>
                <h3 class="stat-value">${{ number_format($totalValue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Low Stock Items</h6>
                <h3 class="stat-value">{{ $lowStockCount }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Out of Stock</h6>
                <h3 class="stat-value">{{ $outOfStockCount }}</h3>
            </div>
        </div>
    </div>

    <div class="card p-3 bg-white">
        <h5>Recently Added Products</h5>
        <table class="table">
            <thead>
                <tr><th>Name</th><th>SKU</th><th>Category</th><th>Quantity</th></tr>
            </thead>
            <tbody>
                @foreach ($recentProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection