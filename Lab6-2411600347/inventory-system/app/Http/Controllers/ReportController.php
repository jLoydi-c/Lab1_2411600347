<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('date_from', now()->subDays(30)->toDateString());
        $to = $request->input('date_to', now()->toDateString());

        // stock movement report
        $movements = $this->getMovements($from, $to);
        $totalIn = $movements->where('quantity', '>', 0)->sum('quantity');
        $totalOut = abs($movements->where('quantity', '<', 0)->sum('quantity'));

        // low stock report
        $lowStockProducts = Product::lowStock()->orderBy('quantity')->get();

        // inventory valuation report (grouped by category)
        $valuation = Product::selectRaw('category, COUNT(*) as product_count, SUM(quantity) as total_units, SUM(quantity * unit_price) as total_value')
            ->groupBy('category')
            ->orderBy('category')
            ->get();
        $grandTotal = $valuation->sum('total_value');

        return view('reports.index', compact(
            'from', 'to', 'movements', 'totalIn', 'totalOut',
            'lowStockProducts', 'valuation', 'grandTotal'
        ));
    }

    // downloads the stock movement report as a CSV file
    public function export(Request $request)
    {
        $from = $request->input('date_from', now()->subDays(30)->toDateString());
        $to = $request->input('date_to', now()->toDateString());
        $movements = $this->getMovements($from, $to);

        return response()->streamDownload(function () use ($movements) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Product', 'SKU', 'Type', 'Quantity', 'Balance After', 'Reference', 'User']);

            foreach ($movements as $m) {
                fputcsv($out, [
                    $m->created_at->format('Y-m-d H:i'),
                    $m->product->name ?? '',
                    $m->product->sku ?? '',
                    $m->type_label,
                    $m->quantity,
                    $m->balance_after,
                    $m->reference_number,
                    $m->user->name ?? 'System',
                ]);
            }

            fclose($out);
        }, 'stock_movement_report.csv', ['Content-Type' => 'text/csv']);
    }

    private function getMovements($from, $to)
    {
        return InventoryTransaction::with(['product', 'user'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->recent()
            ->get();
    }
}