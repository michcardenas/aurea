<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $todaySales = Order::whereDate('created_at', today())->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $newLeads = Lead::whereDate('created_at', today())->count();
        $activeProducts = Product::active()->count();

        $recentOrders = Order::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaySales', 'pendingOrders', 'newLeads', 'activeProducts', 'recentOrders',
        ));
    }
}
