@extends('layouts.app')
@section('title', $product->name)
@section('content')

    <div class="card p-4 bg-white mb-4">
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
                    <span class="badge bg-{{ $product->stock_status_color }} {{ $product->stock_status_color === 'warning' ? 'text-dark' : '' }}">
                        {{ $product->stock_status }}
                    </span>
                </td>
            </tr>
        </table>

        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-login">Edit</a>
            <a href="{{ route('transactions.create') }}" class="btn btn-outline-primary">Record Transaction</a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>

    <!-- transaction history -->
    <div class="card p-4 bg-white">
        <h4 class="mb-3">Transaction History</h4>

        <form method="GET" action="{{ route('products.show', $product) }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-3">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-login flex-fill">Filter</button>
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Balance After</th>
                        <th>Reference</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr>
                            <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
                            <td><span class="badge bg-{{ $t->type_color }}">{{ $t->type_label }}</span></td>
                            <td class="fw-bold {{ $t->quantity > 0 ? 'text-success' : 'text-danger' }}">{{ $t->quantity > 0 ? '+' : '' }}{{ $t->quantity }}</td>
                            <td>{{ $t->balance_after }}</td>
                            <td>{{ $t->reference_number ?? '-' }}</td>
                            <td>{{ $t->user->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No transactions for this product yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $transactions->links() }}</div>
    </div>

@endsection