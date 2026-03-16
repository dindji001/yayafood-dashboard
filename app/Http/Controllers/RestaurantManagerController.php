<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Order;
use App\Models\OpeningHour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestaurantManagerController extends Controller
{
    public function orders(Request $request)
    {
        $user = Auth::user();
        $query = Order::where('restaurant_id', $user->restaurant_id)
            ->with(['user', 'items.dish']);

        // Filtre par période
        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', now()->today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', now()->yesterday());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->has('date')) {
                        $query->whereDate('created_at', $request->date);
                    }
                    break;
            }
        }

        // Filtre par heure (si aujourd'hui ou date spécifique)
        if ($request->has('hour') && $request->hour !== '') {
            $query->whereRaw('HOUR(created_at) = ?', [$request->hour]);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        
        return view('dashboard.restaurant.orders.index', compact('orders'));
    }

    public function menu()
    {
        $user = Auth::user();
        $restaurant = Restaurant::with(['categories.dishes', 'menuSchedules', 'openingHours'])->findOrFail($user->restaurant_id);
        return view('dashboard.restaurant.menu.index', compact('restaurant'));
    }

    public function updateMenuSchedule(Request $request)
    {
        $user = Auth::user();
        $restaurantId = $user->restaurant_id;

        $request->validate([
            'has_daily_menu' => 'nullable',
            'schedules' => 'required_if:has_daily_menu,on|array',
            'schedules.*.day_of_week' => 'required|integer',
            'schedules.*.menu_content' => 'nullable|string',
        ]);

        $restaurant = Restaurant::findOrFail($restaurantId);
        $restaurant->update([
            'has_daily_menu' => $request->has('has_daily_menu')
        ]);

        if ($request->has('has_daily_menu')) {
            foreach ($request->schedules as $scheduleData) {
                \App\Models\MenuSchedule::updateOrCreate(
                    [
                        'restaurant_id' => $restaurantId,
                        'day_of_week' => $scheduleData['day_of_week'],
                    ],
                    [
                        'menu_content' => $scheduleData['menu_content'] ?? '',
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Programme de menu mis à jour avec succès.');
    }

    public function reviews()
    {
        $user = Auth::user();
        $reviews = Review::where('restaurant_id', $user->restaurant_id)
            ->with('user')
            ->latest()
            ->paginate(15);
        
        return view('dashboard.restaurant.reviews.index', compact('reviews'));
    }

    public function settings()
    {
        return redirect()->route('restaurant.settings.profile');
    }

    public function settingsProfile()
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);
        return view('dashboard.restaurant.settings.profile', compact('restaurant'));
    }

    public function settingsHours()
    {
        $user = Auth::user();
        $restaurant = Restaurant::with('openingHours')->findOrFail($user->restaurant_id);
        return view('dashboard.restaurant.settings.hours', compact('restaurant'));
    }

    public function settingsServices()
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);
        return view('dashboard.restaurant.settings.services', compact('restaurant'));
    }

    public function updateOpeningHours(Request $request)
    {
        $user = Auth::user();
        $restaurantId = $user->restaurant_id;

        $request->validate([
            'hours' => 'required|array|size:7',
            'hours.*.day_of_week' => 'required|integer|min:0|max:6',
            'hours.*.open_time' => 'nullable|string',
            'hours.*.close_time' => 'nullable|string',
            'hours.*.is_closed' => 'nullable',
            'hours.*.is_24h' => 'nullable',
        ]);

        foreach ($request->hours as $hourData) {
            OpeningHour::updateOrCreate(
                [
                    'restaurant_id' => $restaurantId,
                    'day_of_week' => $hourData['day_of_week'],
                ],
                [
                    'open_time' => $hourData['open_time'],
                    'close_time' => $hourData['close_time'],
                    'is_closed' => isset($hourData['is_closed']),
                    'is_24h' => isset($hourData['is_24h']),
                ]
            );
        }

        return redirect()->back()->with('success', 'Horaires mis à jour.');
    }

    public function toggleSettings(Request $request)
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);
        
        $request->validate([
            'setting' => 'required|string|in:allow_pay_on_spot,allow_online_payment,is_active',
        ]);

        $setting = $request->setting;
        $restaurant->update([$setting => !$restaurant->$setting]);

        return redirect()->back()->with('success', 'Paramètre mis à jour.');
    }

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
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $data = $request->except(['logo', 'banner', 'qr_code']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
        }

        $restaurant->update($data);

        return redirect()->back()->with('success', 'Informations du restaurant mises à jour.');
    }

    public function requestDeletion(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        \App\Models\AccountDeletionRequest::updateOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['reason' => $request->reason]
        );

        return redirect()->back()->with('success', 'Votre demande de suppression de compte a été envoyée au Super Admin.');
    }

    public function regenerateQrCode()
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);
        
        $restaurant->generateQrCode();

        return redirect()->back()->with('success', 'Nouveau QR Code généré avec succès.');
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
