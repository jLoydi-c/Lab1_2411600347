@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <div class="card p-4 bg-white">
        <h2>{{ $product->name }}</h2>
        <p class="text-muted">SKU: {{ $product->sku }}</p>

        <table class="table">
            <tr><th>Category</th><td>{{ $product->category }}</td></tr>
            <tr><th>Description</th><td>{{ $product->description ?? 'N/A' }}</td></tr>
            <tr><th>Quantity</th><td>{{ $product->quantity }}</td></tr>
            <tr><th>Reorder Level</th><td>{{ $product->reorder_level }}</td></tr>
            <tr><th>Unit Price</th><td>${{ number_format($product->unit_price, 2) }}</td></tr>
            <tr><th>Total Value</th><td>${{ number_format($product->quantity * $product->unit_price, 2) }}</td></tr>
            <tr><th>Supplier</th><td>{{ $product->supplier ?? 'N/A' }}</td></tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($product->isOutOfStock())
                        <span class="badge bg-danger">Out of Stock</span>
                    @elseif ($product->isLowStock())
                        <span class="badge bg-warning text-dark">Low Stock</span>
                    @else
                        <span class="badge bg-success">In Stock</span>
                    @endif
                </td>
            </tr>
        </table>

        <a href="{{ route('products.edit', $product) }}" class="btn btn-login">Edit</a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

@endsection