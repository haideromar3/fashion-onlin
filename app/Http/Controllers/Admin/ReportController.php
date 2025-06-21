<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->subMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $orders = Order::whereBetween('created_at', [$from, $to])->get();

        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();

        $totalProductsSold = $orders->flatMap(function ($order) {
            return $order->items;
        })->sum('quantity');

        $productStats = Product::with(['orders' => function ($q) use ($from, $to) {
            $q->whereBetween('orders.created_at', [$from, $to]);
        }])->get()->map(function ($product) {
            $quantity = $product->orders->sum('pivot.quantity');
            $revenue = $quantity * $product->price;
            $product->revenue = $revenue;
            $product->sales = $quantity;
            return $product;
        });

        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date')
            ->toArray();

        return view('admin.reports.index', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalSales' => $totalRevenue,
            'totalProductsSold' => $totalProductsSold,
            'productStats' => $productStats,
            'dailyRevenue' => $dailyRevenue,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function exportPDF(Request $request)
    {
        $from = $request->from ?? now()->subMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $orders = Order::whereBetween('created_at', [$from, $to])->get();
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();

        $productStats = Product::with(['orders' => function ($q) use ($from, $to) {
            $q->whereBetween('orders.created_at', [$from, $to]);
        }])->get()->map(function ($product) {
            $quantity = $product->orders->sum('pivot.quantity');
            $revenue = $quantity * $product->price;
            $product->revenue = $revenue;
            $product->sales = $quantity;
            return $product;
        });

        $pdf = Pdf::loadView('admin.reports.export_pdf', compact(
            'orders', 'from', 'to', 'totalOrders', 'totalRevenue', 'productStats'
        ));

        return $pdf->download('sales-report.pdf');
    }

    public function exportCSV(Request $request)
    {
        return Excel::download(new OrdersExport($request->from, $request->to), 'sales-report.xlsx');
    }
}
