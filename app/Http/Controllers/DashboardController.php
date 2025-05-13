<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalOrders = Order::count();
        $totalProducts = Products::count();
        $totalUsers = User::count();
        $totalClients = Client::count();

        $totalRevenue = Order::sum('total');
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Orders grouped by state/region
        $ordersByRegion = DB::table('orders')
            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
            ->join('states', 'addresses.state_id', '=', 'states.id')
            ->select('states.name as region', DB::raw('count(orders.id) as count'))
            ->groupBy('states.name')
            ->orderByDesc('count')
            ->get();

        $lowStockProducts = Products::where('stock', '<', 10)->count();

        return view('dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'totalClients',
            'totalRevenue',
            'ordersByStatus',
            'ordersByRegion',
            'lowStockProducts'
        ));
    }
}
