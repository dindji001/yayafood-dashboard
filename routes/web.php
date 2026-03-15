<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware(CheckRole::class . ':super_admin')->group(function () {
        Route::get('/admin/restaurants-list', [AdminController::class, 'restaurants'])->name('admin.restaurants.index');
        Route::get('/admin/restaurants/{id}', [AdminController::class, 'restaurantDetail'])->name('admin.restaurants.show');
        
        Route::post('/admin/restaurants', [AdminController::class, 'createRestaurant'])->name('admin.restaurants.create');
        Route::put('/admin/restaurants/{id}', [AdminController::class, 'updateRestaurant'])->name('admin.restaurants.update');
        Route::delete('/admin/restaurants/{id}', [AdminController::class, 'deleteRestaurant'])->name('admin.restaurants.delete');
        Route::post('/admin/restaurants/{id}/toggle', [AdminController::class, 'toggleRestaurantStatus'])->name('admin.restaurants.toggle');
        
        Route::post('/admin/users', [AdminController::class, 'createRestaurantUser'])->name('admin.users.create');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });

    // Restaurant Routes
    Route::middleware(CheckRole::class . ':restaurant')->group(function () {
        Route::post('/restaurant/info', [RestaurantManagerController::class, 'updateInfo'])->name('restaurant.info.update');
        
        Route::post('/restaurant/categories', [RestaurantManagerController::class, 'createCategory'])->name('restaurant.categories.create');
        Route::put('/restaurant/categories/{id}', [RestaurantManagerController::class, 'updateCategory'])->name('restaurant.categories.update');
        Route::delete('/restaurant/categories/{id}', [RestaurantManagerController::class, 'deleteCategory'])->name('restaurant.categories.delete');
        
        Route::post('/restaurant/dishes', [RestaurantManagerController::class, 'createDish'])->name('restaurant.dishes.create');
        Route::put('/restaurant/dishes/{id}', [RestaurantManagerController::class, 'updateDish'])->name('restaurant.dishes.update');
        Route::delete('/restaurant/dishes/{id}', [RestaurantManagerController::class, 'deleteDish'])->name('restaurant.dishes.delete');
        Route::post('/restaurant/dishes/{id}/toggle', [RestaurantManagerController::class, 'toggleDishAvailability'])->name('restaurant.dishes.toggle');
        
        Route::put('/restaurant/orders/{id}/status', [RestaurantManagerController::class, 'updateOrderStatus'])->name('restaurant.orders.status');
        Route::post('/restaurant/reviews/{id}/reply', [RestaurantManagerController::class, 'replyToReview'])->name('restaurant.reviews.reply');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
