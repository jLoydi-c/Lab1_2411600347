@extends('layouts.app')
@section('title', 'Transactions')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Inventory Transactions</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-login">Record Transaction</a>
    </div>

    <!-- filter form -->
    <form method="GET" action="{{ route('transactions.index') }}" class="card p-3 mb-3 bg-white">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Product</label>
                <select name="product_id" class="form-select">
                    <option value="">All Products</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-login flex-fill">Filter</button>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                        <td><a href="{{ route('products.show', $transaction->product_id) }}">{{ $transaction->product->name }}</a></td>
                        <td><span class="badge bg-{{ $transaction->type_color }}">{{ $transaction->type_label }}</span></td>
                        <td>
                            @if ($transaction->quantity > 0)
                                <span class="text-success fw-bold">+{{ $transaction->quantity }}</span>
                            @else
                                <span class="text-danger fw-bold">{{ $transaction->quantity }}</span>
                            @endif
                        </td>
                        <td>{{ $transaction->reference_number ?? '-' }}</td>
                        <td>{{ $transaction->user->name ?? 'System' }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            @if (auth()->user()->is_admin)
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this transaction and reverse its stock change?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $transactions->links() }}

@endsection