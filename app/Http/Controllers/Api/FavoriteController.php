<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::where('user_id', $request->user()->id)
            ->with('restaurant')
            ->get();
        return response()->json($favorites);
    }

    public function toggle(Request $request, $restaurantId)
    {
        $user = $request->user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Retiré des favoris', 'is_favorite' => false]);
        }

        Favorite::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId
        ]);

        return response()->json(['message' => 'Ajouté aux favoris', 'is_favorite' => true]);
    }
}
