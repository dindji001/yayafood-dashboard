<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantManagerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::post('/admin/restaurants', [AdminController::class, 'createRestaurant'])->name('admin.restaurants.create');
    Route::post('/admin/users', [AdminController::class, 'createRestaurantUser'])->name('admin.users.create');

    // Restaurant Routes
    Route::post('/restaurant/info', [RestaurantManagerController::class, 'updateInfo'])->name('restaurant.info.update');
    Route::post('/restaurant/categories', [RestaurantManagerController::class, 'createCategory'])->name('restaurant.categories.create');
    Route::post('/restaurant/dishes', [RestaurantManagerController::class, 'createDish'])->name('restaurant.dishes.create');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
