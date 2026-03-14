<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\User;

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
                'total_revenue' => Order::where('status', 'closed')->sum('total_amount'),
            ];
            $restaurants = Restaurant::withCount('orders')->get();
            return view('dashboard.super_admin', compact('stats', 'restaurants'));
        }

        if ($user->role === 'restaurant') {
            $restaurant = Restaurant::withCount('orders')->findOrFail($user->restaurant_id);
            $stats = [
                'orders_today' => Order::where('restaurant_id', $user->restaurant_id)
                    ->whereDate('created_at', now()->today())
                    ->count(),
                'revenue_today' => Order::where('restaurant_id', $user->restaurant_id)
                    ->where('status', 'closed')
                    ->whereDate('created_at', now()->today())
                    ->sum('total_amount'),
                'average_rating' => $restaurant->averageRating(),
            ];
            $liveOrders = Order::where('restaurant_id', $user->restaurant_id)
                ->whereIn('status', ['pending', 'preparing', 'ready'])
                ->with('items.dish')
                ->get();
            $reviews = $restaurant->reviews()->with('user')->orderBy('created_at', 'desc')->get();
            return view('dashboard.restaurant', compact('stats', 'restaurant', 'liveOrders', 'reviews'));
        }

        return redirect('/');
    }
}
