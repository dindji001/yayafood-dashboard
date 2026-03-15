<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class RestaurantManagerController extends Controller
{
    public function replyToReview(Request $request, $id)
    {
        $user = Auth::user();
        $review = Review::where('restaurant_id', $user->restaurant_id)->findOrFail($id);

        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply' => $request->reply,
            'replied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Votre réponse a été publiée.');
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'qr_code' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['logo', 'banner', 'qr_code']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
        }

        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('restaurants/qrcodes', 'public');
        }

        $restaurant->update($data);

        return redirect()->back()->with('success', 'Informations du restaurant mises à jour.');
    }

    public function createCategory(Request $request)
    {
        $user = Auth::user();
        $request->validate(['name' => 'required|string|max:255']);

        Category::create([
            'name' => $request->name,
            'restaurant_id' => $user->restaurant_id,
        ]);

        return redirect()->back()->with('success', 'Catégorie créée.');
    }

    public function updateCategory(Request $request, $id)
    {
        $user = Auth::user();
        $category = Category::where('restaurant_id', $user->restaurant_id)->findOrFail($id);

        $request->validate(['name' => 'required|string|max:255']);

        $category->update($request->only('name'));

        return redirect()->back()->with('success', 'Catégorie mise à jour.');
    }

    public function deleteCategory($id)
    {
        $user = Auth::user();
        $category = Category::where('restaurant_id', $user->restaurant_id)->findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée.');
    }

    public function createDish(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Verify category belongs to this restaurant
        $category = Category::where('restaurant_id', $user->restaurant_id)->findOrFail($request->category_id);

        Dish::create($request->all());

        return redirect()->back()->with('success', 'Plat ajouté au menu.');
    }

    public function updateDish(Request $request, $id)
    {
        $user = Auth::user();
        $dish = Dish::whereHas('category', function($q) use ($user) {
            $q->where('restaurant_id', $user->restaurant_id);
        })->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $dish->update($request->all());

        return redirect()->back()->with('success', 'Plat mis à jour.');
    }

    public function deleteDish($id)
    {
        $user = Auth::user();
        $dish = Dish::whereHas('category', function($q) use ($user) {
            $q->where('restaurant_id', $user->restaurant_id);
        })->findOrFail($id);

        $dish->delete();

        return redirect()->back()->with('success', 'Plat supprimé.');
    }

    public function toggleDishAvailability($id)
    {
        $user = Auth::user();
        $dish = Dish::whereHas('category', function($q) use ($user) {
            $q->where('restaurant_id', $user->restaurant_id);
        })->findOrFail($id);

        $dish->update(['is_available' => !$dish->is_available]);

        return redirect()->back()->with('success', 'Disponibilité du plat mise à jour.');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('restaurant_id', $user->restaurant_id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,closed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }
}
