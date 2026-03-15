<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::where('role', 'client')
            ->withCount(['orders', 'reviews'])
            ->get();

        return view('dashboard.admin.users.index', compact('users'));
    }

    public function userDetail($id)
    {
        $user = User::withCount(['orders', 'reviews'])
            ->with(['orders.restaurant', 'reviews.restaurant'])
            ->findOrFail($id);

        // Plat le plus consommé par l'utilisateur
        $favoriteDish = OrderItem::whereHas('order', function($q) use ($id) {
                $q->where('user_id', $id);
            })
            ->select('dish_id', DB::raw('count(*) as total'))
            ->groupBy('dish_id')
            ->orderBy('total', 'desc')
            ->with('dish')
            ->first();

        // Restaurant le plus visité
        $favoriteRestaurant = Order::where('user_id', $id)
            ->select('restaurant_id', DB::raw('count(*) as total'))
            ->groupBy('restaurant_id')
            ->orderBy('total', 'desc')
            ->with('restaurant')
            ->first();

        // Statistiques globales de l'utilisateur
        $userStats = [
            'total_spent' => Order::where('user_id', $id)->where('status', 'served')->sum('total_amount'),
            'avg_rating' => $user->reviews()->avg('rating') ?: 0,
            'orders_cancelled' => Order::where('user_id', $id)->where('status', 'cancelled')->count(),
        ];

        return view('dashboard.admin.users.show', compact('user', 'favoriteDish', 'favoriteRestaurant', 'userStats'));
    }

    public function restaurants()
    {
        $restaurants = Restaurant::withCount(['orders', 'reviews', 'categories'])
            ->with(['reviews' => function($q) {
                $q->latest()->limit(5);
            }])
            ->get();

        return view('dashboard.admin.restaurants.index', compact('restaurants'));
    }

    public function restaurantDetail($id)
    {
        $restaurant = Restaurant::withCount(['orders', 'reviews', 'categories'])
            ->with(['categories.dishes', 'reviews.user', 'orders.user'])
            ->findOrFail($id);

        // Statistiques avancées des commandes
        $orderStats = [
            'paid' => Order::where('restaurant_id', $id)->where('status', 'served')->count(),
            'cancelled' => Order::where('restaurant_id', $id)->where('status', 'cancelled')->count(),
            'failed' => Order::where('restaurant_id', $id)->where('status', 'failed')->count(), // Supposons un état failed
            'pending' => Order::where('restaurant_id', $id)->whereIn('status', ['pending', 'preparing', 'ready'])->count(),
            'total_revenue' => Order::where('restaurant_id', $id)->where('status', 'served')->sum('total_amount'),
        ];

        // Nombre de plats disponibles vs total
        $dishStats = [
            'total' => DB::table('dishes')
                ->join('categories', 'dishes.category_id', '=', 'categories.id')
                ->where('categories.restaurant_id', $id)
                ->count(),
            'available' => DB::table('dishes')
                ->join('categories', 'dishes.category_id', '=', 'categories.id')
                ->where('categories.restaurant_id', $id)
                ->where('dishes.is_available', true)
                ->count(),
        ];

        // Visites par jour (Simulation via les commandes créées par jour si pas de table visits)
        $visitsPerDay = Order::where('restaurant_id', $id)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return view('dashboard.admin.restaurants.show', compact('restaurant', 'orderStats', 'dishStats', 'visitsPerDay'));
    }

    public function createRestaurant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Restaurant::create($request->all());

        return redirect()->back()->with('success', 'Restaurant créé avec succès.');
    }

    public function updateRestaurant(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update($request->all());

        return redirect()->back()->with('success', 'Restaurant mis à jour.');
    }

    public function deleteRestaurant($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return redirect()->back()->with('success', 'Restaurant supprimé.');
    }

    public function toggleRestaurantStatus($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['is_active' => !$restaurant->is_active]);

        return redirect()->back()->with('success', 'Statut du restaurant mis à jour.');
    }

    public function createRestaurantUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'restaurant',
            'restaurant_id' => $request->restaurant_id,
        ]);

        return redirect()->back()->with('success', 'Restauranteur créé et assigné avec succès.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'super_admin') {
            return redirect()->back()->with('error', 'Impossible de supprimer un super-administrateur.');
        }
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé.');
    }
}
