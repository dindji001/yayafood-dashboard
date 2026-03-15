<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\User;

use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            $stats = [
                'restaurants_count' => Restaurant::count(),
                'users_count' => User::where('role', 'client')->count(),
                'orders_count' => Order::count(),
                'total_revenue' => Order::where('status', 'served')->sum('total_amount'),
            ];

            // Données pour le graphique (7 derniers jours)
            $chartData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

            $recentOrders = Order::with(['user', 'restaurant'])->latest()->limit(5)->get();
            $recentUsers = User::where('role', 'client')->latest()->limit(5)->get();
            
            $restaurants = Restaurant::withCount('orders')->get();
            $users = User::where('role', '!=', 'super_admin')->with('restaurant')->get();

            return view('dashboard.super_admin', compact('stats', 'restaurants', 'users', 'chartData', 'recentOrders', 'recentUsers'));
        }

        if ($user->role === 'restaurant') {
            $restaurantId = $user->restaurant_id;
            $restaurant = Restaurant::with(['categories.dishes', 'orders.items.dish', 'reviews.user'])
                ->withCount('orders')
                ->findOrFail($restaurantId);

            $stats = [
                'orders_today' => Order::where('restaurant_id', $restaurantId)
                    ->whereDate('created_at', Carbon::today())
                    ->count(),
                'revenue_today' => Order::where('restaurant_id', $restaurantId)
                    ->where('status', 'served')
                    ->whereDate('created_at', Carbon::today())
                    ->sum('total_amount'),
                'average_rating' => $restaurant->averageRating(),
                'total_orders' => $restaurant->orders_count,
            ];

            // Top plats vendus
            $topDishes = OrderItem::whereHas('order', function($q) use ($restaurantId) {
                    $q->where('restaurant_id', $restaurantId);
                })
                ->select('dish_id', DB::raw('count(*) as total'))
                ->groupBy('dish_id')
                ->orderBy('total', 'desc')
                ->with('dish')
                ->limit(5)
                ->get();

            // Revenus 7 derniers jours
            $revenueChart = Order::where('restaurant_id', $restaurantId)
                ->where('status', 'served')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            $liveOrders = Order::where('restaurant_id', $restaurantId)
                ->whereIn('status', ['pending', 'preparing', 'ready'])
                ->with(['items.dish', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $reviews = $restaurant->reviews()->with('user')->orderBy('created_at', 'desc')->get();

            return view('dashboard.restaurant', compact('stats', 'restaurant', 'liveOrders', 'reviews', 'topDishes', 'revenueChart'));
        }

        return redirect('/');
    }
}
