<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Restaurant;
use App\Models\Review;
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
        ]);

        $data = $request->except(['logo', 'banner']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
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

    public function createDish(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Dish::create($request->all());

        return redirect()->back()->with('success', 'Plat ajouté au menu.');
    }
}
