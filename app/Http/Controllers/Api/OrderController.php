<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Requests\Api\Order\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

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

    public function store(StoreOrderRequest $request)
    {
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

    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Vérifier les permissions (soit super_admin, soit le resto de la commande)
        $user = $request->user();
        if ($user->role !== 'super_admin' && ($user->role !== 'restaurant' || $user->restaurant_id !== $order->restaurant_id)) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Statut mis à jour',
            'order' => $order
        ]);
    }
}
