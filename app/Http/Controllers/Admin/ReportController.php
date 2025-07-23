<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show sales report.
     */
    public function sales(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        // Sales summary
        $totalSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
            
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Daily sales chart
        $dailySales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Top selling products
        $topProducts = Product::withCount(['orderItems' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->where('payment_status', 'paid')
                      ->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.reports.sales', compact(
            'totalSales',
            'totalOrders', 
            'averageOrderValue',
            'dailySales',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show inventory report.
     */
    public function inventory()
    {
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->get();
            
        $outOfStockProducts = Product::where('stock', 0)->get();
        
        $totalProducts = Product::count();
        $totalValue = Product::selectRaw('SUM(price * stock) as total')->first()->total ?? 0;
        
        return view('admin.reports.inventory', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'totalProducts',
            'totalValue'
        ));
    }

    /**
     * Show customer report.
     */
    public function customers(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Top customers by order value
        $topCustomers = User::where('role', 'customer')
            ->withSum(['orders' => function($query) use ($startDate, $endDate) {
                $query->where('payment_status', 'paid')
                      ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.reports.customers', compact(
            'newCustomers',
            'totalCustomers',
            'topCustomers',
            'startDate',
            'endDate'
        ));
    }
}
