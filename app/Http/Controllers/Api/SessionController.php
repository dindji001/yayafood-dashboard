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

        $restaurant = \App\Models\Restaurant::with('openingHours')->findOrFail($request->restaurant_id);
        
        if (!$restaurant->is_active) {
            return response()->json([
                'message' => 'Ce restaurant est temporairement fermé.',
                'code' => 'RESTAURANT_INACTIVE'
            ], 403);
        }

        // Vérifier les horaires d'ouverture
        if (!$this->isCurrentlyOpen($restaurant)) {
            return response()->json([
                'message' => 'Ce restaurant est actuellement fermé.',
                'code' => 'RESTAURANT_CLOSED',
                'restaurant' => $restaurant
            ], 403);
        }

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

    private function isCurrentlyOpen($restaurant)
    {
        if ($restaurant->openingHours->isEmpty()) {
            return true;
        }

        $now = now();
        $dayOfWeek = $now->dayOfWeek; // Carbon: 0=Sun, 1=Mon, ..., 6=Sat

        $todayHours = $restaurant->openingHours->where('day_of_week', $dayOfWeek)->first();

        if (!$todayHours) {
            return true;
        }

        if ($todayHours->is_24h) {
            return true;
        }

        if ($todayHours->is_closed) {
            return false;
        }

        if ($todayHours->open_time && $todayHours->close_time) {
            $open = \Carbon\Carbon::createFromTimeString($todayHours->open_time);
            $close = \Carbon\Carbon::createFromTimeString($todayHours->close_time);

            // Gérer le cas où le restaurant ferme après minuit
            if ($close->lessThan($open)) {
                return $now->greaterThanOrEqualTo($open) || $now->lessThanOrEqualTo($close);
            }

            return $now->between($open, $close);
        }

        return false;
    }
}
