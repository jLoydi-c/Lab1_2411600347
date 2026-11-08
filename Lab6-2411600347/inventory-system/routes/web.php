<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\DashboardApiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);

    Route::resource('transactions', InventoryTransactionController::class)
        ->except(['edit', 'update']);

    Route::get('/adjustments/create', [AdjustmentController::class, 'create'])->name('adjustments.create');
    Route::post('/adjustments', [AdjustmentController::class, 'store'])->name('adjustments.store');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    // JSON endpoints for the live dashboard (kept here so they use the normal login session)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/stats', [DashboardApiController::class, 'stats'])->name('dashboard.stats');
        Route::get('/products/low-stock', [DashboardApiController::class, 'lowStock'])->name('products.lowStock');
        Route::get('/transactions/recent', [DashboardApiController::class, 'recentTransactions'])->name('transactions.recent');
        Route::get('/products/{product}', [DashboardApiController::class, 'productShow'])->name('products.show');
    });
});

require __DIR__.'/auth.php';