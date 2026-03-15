<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['restaurant', 'items.dish'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with(['restaurant', 'items.dish'])
            ->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.dish_id' => 'required|exists:dishes,id',
            'items.*.quantity' => 'required|integer|min:1',
            'table_number' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'payment_timing' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'restaurant_id' => $request->restaurant_id,
            'total_amount' => 0, // Sera calculé plus bas
            'status' => 'pending',
            'table_number' => $request->table_number,
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_timing' => $request->payment_timing ?? 'after_service',
            'payment_status' => 'pending',
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $dish = \App\Models\Dish::find($item['dish_id']);
            $price = $dish->price * $item['quantity'];
            $total += $price;

            OrderItem::create([
                'order_id' => $order->id,
                'dish_id' => $item['dish_id'],
                'quantity' => $item['quantity'],
                'price' => $dish->price,
            ]);
        }

        $order->update(['total_amount' => $total]);

        return response()->json([
            'message' => 'Commande passée avec succès',
            'order' => $order->load('items.dish')
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Vérifier les permissions (soit super_admin, soit le resto de la commande)
        $user = $request->user();
        if ($user->role !== 'super_admin' && ($user->role !== 'restaurant' || $user->restaurant_id !== $order->restaurant_id)) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,preparing,ready,served,closed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Statut mis à jour',
            'order' => $order
        ]);
    }
}
