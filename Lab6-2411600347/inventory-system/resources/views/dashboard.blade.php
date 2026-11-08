@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0">Welcome back, {{ auth()->user()->name }}</h2>
        <small class="text-muted">
            Last updated: <span id="lastUpdated">{{ now()->format('h:i:s A') }}</span>
            <span id="updateStatus" class="text-danger"></span>
        </small>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Total Products</h6>
                <h3 class="stat-value" id="stat-total-products" data-value="{{ $totalProducts }}">{{ $totalProducts }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Total Inventory Value</h6>
                <h3 class="stat-value" id="stat-total-value" data-value="{{ round($totalValue, 2) }}">${{ number_format($totalValue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Low Stock Items</h6>
                <h3 class="stat-value" id="stat-low-stock" data-value="{{ $lowStockCount }}">{{ $lowStockCount }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <h6 class="stat-title">Out of Stock</h6>
                <h3 class="stat-value" id="stat-out-of-stock" data-value="{{ $outOfStockCount }}">{{ $outOfStockCount }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card chart-card">
                <div class="card-header app-card-header">Low Stock Alerts</div>
                <ul class="list-group list-group-flush" id="lowStockList">
                    @forelse ($lowStockProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            <span class="badge bg-{{ $product->stock_status_color }} {{ $product->stock_status_color === 'warning' ? 'text-dark' : '' }}">
                                {{ $product->quantity }} left (reorder at {{ $product->reorder_level }})
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No low stock items.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card chart-card">
                <div class="card-header app-card-header">Recent Transactions</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Date</th><th>Product</th><th>Type</th><th>Qty</th><th>User</th></tr>
                        </thead>
                        <tbody id="recentTransactionsBody">
                            @forelse ($recentTransactions as $t)
                                <tr>
                                    <td>{{ $t->created_at->format('M d, h:i A') }}</td>
                                    <td><a href="{{ route('products.show', $t->product_id) }}">{{ $t->product->name }}</a></td>
                                    <td><span class="badge bg-{{ $t->type_color }}">{{ $t->type_label }}</span></td>
                                    <td class="fw-bold {{ $t->quantity > 0 ? 'text-success' : 'text-danger' }}">{{ $t->quantity > 0 ? '+' : '' }}{{ $t->quantity }}</td>
                                    <td>{{ $t->user->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">No transactions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    window.dashboardUrls = {
        stats: "{{ route('api.dashboard.stats') }}",
        lowStock: "{{ route('api.products.lowStock') }}",
        recent: "{{ route('api.transactions.recent') }}"
    };
</script>
<script src="{{ asset('js/dashboard-live.js') }}"></script>
@endpush