@extends('layouts.app')
@section('title', 'Transaction #' . $transaction->id)
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Transaction #{{ $transaction->id }}</h2>
        <div>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Back to List</a>
            @if (auth()->user()->is_admin)
                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this transaction and reverse its stock change?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card p-3 bg-white h-100">
                <h5>Transaction Details</h5>
                <table class="table table-sm mb-0">
                    <tr><th>Type</th><td><span class="badge bg-{{ $transaction->type_color }}">{{ $transaction->type_label }}</span></td></tr>
                    <tr><th>Quantity</th><td>{{ $transaction->quantity > 0 ? '+' : '' }}{{ $transaction->quantity }}</td></tr>
                    <tr><th>Balance After</th><td>{{ $transaction->balance_after }}</td></tr>
                    <tr><th>Reference</th><td>{{ $transaction->reference_number ?? '-' }}</td></tr>
                    <tr><th>Reason</th><td>{{ $transaction->reason ? ucwords(str_replace('_', ' ', $transaction->reason)) : '-' }}</td></tr>
                    <tr><th>Notes</th><td>{{ $transaction->notes ?? '-' }}</td></tr>
                    <tr><th>Date</th><td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td></tr>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 bg-white h-100">
                <h5>Product</h5>
                <table class="table table-sm mb-3">
                    <tr><th>Name</th><td>{{ $transaction->product->name }}</td></tr>
                    <tr><th>SKU</th><td>{{ $transaction->product->sku }}</td></tr>
                    <tr><th>Category</th><td>{{ $transaction->product->category }}</td></tr>
                    <tr><th>Current Stock</th><td>{{ $transaction->product->quantity }}</td></tr>
                </table>
                <a href="{{ route('products.show', $transaction->product_id) }}" class="btn btn-login btn-sm">View Product</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 bg-white h-100">
                <h5>Performed By</h5>
                <table class="table table-sm mb-0">
                    <tr><th>Name</th><td>{{ $transaction->user->name ?? 'System' }}</td></tr>
                    <tr><th>Email</th><td>{{ $transaction->user->email ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

@endsection