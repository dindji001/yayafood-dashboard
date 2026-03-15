<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
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
