<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Review;
use App\Models\OpeningHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function reviews(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        $reviews = Review::where('restaurant_id', $restaurantId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($reviews);
    }

    public function stats(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        $restaurant = Restaurant::withCount('orders')->findOrFail($restaurantId);

        return response()->json([
            'orders_today' => Order::where('restaurant_id', $restaurantId)
                ->whereDate('created_at', now()->today())
                ->count(),
            'revenue_today' => Order::where('restaurant_id', $restaurantId)
                ->where('status', 'closed')
                ->whereDate('created_at', now()->today())
                ->sum('total_amount'),
            'average_rating' => $restaurant->averageRating(),
        ]);
    }

    public function menu(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        return response()->json(
            Category::where('restaurant_id', $restaurantId)
                ->with('dishes')
                ->orderBy('sort_order')
                ->get()
        );
    }

    public function updateInfo(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        $restaurant = Restaurant::findOrFail($restaurantId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('restaurants/logos', 'public');
            $request->merge(['logo' => $logoPath]);
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('restaurants/banners', 'public');
            $request->merge(['banner' => $bannerPath]);
        }

        $restaurant->update($request->all());
        
        if ($request->expectsJson()) {
            return response()->json($restaurant);
        }
        return back()->with('success', 'Profil mis à jour');
    }

    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sort_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'restaurant_id' => $request->user()->restaurant_id,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json($category, 201);
    }

    public function createDish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('dishes', 'public');
            $request->merge(['image' => $path]);
        }

        // Vérifier que la catégorie appartient bien au restaurant de l'utilisateur
        $category = Category::findOrFail($request->category_id);
        if ($category->restaurant_id !== $request->user()->restaurant_id) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        $dish = Dish::create($request->all());
        return response()->json($dish, 201);
    }

    public function liveOrders(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        $orders = Order::where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->with(['items.dish', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($orders);
    }

    public function updateOpeningHours(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        $validator = Validator::make($request->all(), [
            'hours' => 'required|array|size:7',
            'hours.*.day_of_week' => 'required|integer|min:0|max:6',
            'hours.*.open_time' => 'nullable|string',
            'hours.*.close_time' => 'nullable|string',
            'hours.*.is_closed' => 'required|boolean',
            'hours.*.is_24h' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        foreach ($request->hours as $hourData) {
            OpeningHour::updateOrCreate(
                [
                    'restaurant_id' => $restaurantId,
                    'day_of_week' => $hourData['day_of_week'],
                ],
                [
                    'open_time' => $hourData['open_time'],
                    'close_time' => $hourData['close_time'],
                    'is_closed' => isset($hourData['is_closed']) && ($hourData['is_closed'] == 1 || $hourData['is_closed'] == 'true' || $hourData['is_closed'] == true),
                    'is_24h' => isset($hourData['is_24h']) && ($hourData['is_24h'] == 1 || $hourData['is_24h'] == 'true' || $hourData['is_24h'] == true),
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Horaires mis à jour avec succès']);
        }
        return back()->with('success', 'Horaires mis à jour');
    }

    public function getOpeningHours(Request $request)
    {
        $restaurantId = $request->user()->restaurant_id;
        $hours = OpeningHour::where('restaurant_id', $restaurantId)
            ->orderBy('day_of_week')
            ->get();
        return response()->json($hours);
    }
}
