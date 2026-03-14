<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantAccessSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $user = $request->user();
        
        // Supprimer l'ancienne session si elle existe
        RestaurantAccessSession::where('user_id', $user->id)->delete();

        $session = RestaurantAccessSession::create([
            'user_id' => $user->id,
            'restaurant_id' => $request->restaurant_id,
            'expires_at' => now()->addHours(2),
        ]);

        return response()->json([
            'message' => 'Session démarrée',
            'expires_at' => $session->expires_at,
            'restaurant_id' => $session->restaurant_id
        ]);
    }

    public function status(Request $request)
    {
        $session = RestaurantAccessSession::where('user_id', $request->user()->id)->first();
        
        if (!$session || $session->isExpired()) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active' => true,
            'restaurant_id' => $session->restaurant_id,
            'expires_at' => $session->expires_at,
            'remaining_minutes' => now()->diffInMinutes($session->expires_at)
        ]);
    }
}
