@extends('layouts.app')
@section('title', 'Reports')
@section('content')

    <h2 class="mb-3">Inventory Reports</h2>

    <!-- 1. stock movement report -->
    <div class="card p-4 bg-white mb-4">
        <h4>Stock Movement Report</h4>

        <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-3">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-login">Generate</button>
                <a href="{{ route('reports.export', ['date_from' => $from, 'date_to' => $to]) }}" class="btn btn-outline-primary">Export CSV</a>
            </div>
        </form>

        <p class="mb-2">
            Total stock in: <strong class="text-success">+{{ $totalIn }}</strong> |
            Total stock out: <strong class="text-danger">-{{ $totalOut }}</strong> |
            Transactions: <strong>{{ $movements->count() }}</strong>
        </p>

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr><th>Date</th><th>Product</th><th>Type</th><th>Quantity</th><th>Balance After</th><th>User</th></tr>
                </thead>
                <tbody>
                    @forelse ($movements as $m)
                        <tr>
                            <td>{{ $m->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $m->product->name ?? 'Deleted product' }}</td>
                            <td><span class="badge bg-{{ $m->type_color }}">{{ $m->type_label }}</span></td>
                            <td class="fw-bold {{ $m->quantity > 0 ? 'text-success' : 'text-danger' }}">{{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                            <td>{{ $m->balance_after }}</td>
                            <td>{{ $m->user->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No movements in this date range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. low stock report -->
    <div class="card p-4 bg-white mb-4">
        <h4>Low Stock Report</h4>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr><th>SKU</th><th>Product</th><th>Quantity</th><th>Reorder Level</th><th>Supplier</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($lowStockProducts as $p)
                        <tr>
                            <td>{{ $p->sku }}</td>
                            <td><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a></td>
                            <td>{{ $p->quantity }}</td>
                            <td>{{ $p->reorder_level }}</td>
                            <td>{{ $p->supplier ?? '-' }}</td>
                            <td><span class="badge bg-{{ $p->stock_status_color }} {{ $p->stock_status_color === 'warning' ? 'text-dark' : '' }}">{{ $p->stock_status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No low stock products.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. inventory valuation report -->
    <div class="card p-4 bg-white">
        <h4>Inventory Valuation Report</h4>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr><th>Category</th><th>Products</th><th>Total Units</th><th>Total Value</th></tr>
                </thead>
                <tbody>
                    @foreach ($valuation as $row)
                        <tr>
                            <td>{{ $row->category }}</td>
                            <td>{{ $row->product_count }}</td>
                            <td>{{ $row->total_units }}</td>
                            <td>${{ number_format($row->total_value, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Grand Total</td>
                        <td>${{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection