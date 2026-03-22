<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders'   => Order::whereDate('created_at', today())->count(),
            'today_revenue'  => Order::whereDate('created_at', today())->sum('total_amount'),
            'total_items'    => MenuItem::count(),
            'total_cats'     => Category::count(),
        ];
        $recentOrders = Order::latest()->take(10)->get();
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}