<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BuildRight Hardware')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark app-navbar px-3">
        <a class="navbar-brand" href="{{ route('dashboard') }}">BuildRight Hardware</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('transactions.index') }}">Transactions</a></li>
                @if (auth()->user()->is_admin)
                    <li class="nav-item"><a class="nav-link" href="{{ route('adjustments.create') }}">Adjust Stock</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}">Reports</a></li>
            </ul>

            <ul class="navbar-nav align-items-lg-center">

                <!-- notification bell -->
                @php $unread = auth()->user()->unreadNotifications; @endphp
                <li class="nav-item dropdown me-lg-3">
                    <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="bi bi-bell fs-5"></i>
                        @if ($unread->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unread->count() }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notif-menu p-0">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <strong>Low Stock Alerts</strong>
                            @if ($unread->count() > 0)
                                <form method="POST" action="{{ route('notifications.readAll') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm p-0">Mark all as read</button>
                                </form>
                            @endif
                        </div>

                        @forelse ($unread->take(5) as $notification)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="dropdown-item notif-item">
                                    <div class="fw-semibold">{{ $notification->data['product_name'] }}</div>
                                    <div class="small text-muted">{{ $notification->data['message'] }}</div>
                                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                </button>
                            </form>
                        @empty
                            <div class="px-3 py-3 text-muted small">No new alerts.</div>
                        @endforelse
                    </div>
                </li>

                <!-- user dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>