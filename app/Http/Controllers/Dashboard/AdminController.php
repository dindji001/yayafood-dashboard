<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function stats(Request $request)
    {
        $query = Order::query();

        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', now()->today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
            }
        }

        return response()->json([
            'restaurants_count' => Restaurant::count(),
            'users_count' => User::where('role', 'client')->count(),
            'orders_count' => $query->count(),
            'total_revenue' => (clone $query)->where('status', 'closed')->sum('total_amount'),
        ]);
    }

    public function restaurantsList()
    {
        return response()->json(Restaurant::with(['openingHours'])->withCount('orders')->get());
    }

    public function restaurantDetails($id)
    {
        $restaurant = Restaurant::with(['openingHours', 'users', 'categories.dishes'])->findOrFail($id);
        return response()->json($restaurant);
    }

    public function createRestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $restaurant = Restaurant::create($request->all());
        return response()->json($restaurant, 201);
    }

    public function usersList()
    {
        return response()->json(User::where('role', '!=', 'super_admin')->get());
    }

    public function createRestaurantUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'restaurant',
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json($user, 201);
    }
}
