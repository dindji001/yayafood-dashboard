<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RestaurantController as ClientRestaurantController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\RestaurantController as ManagerRestaurantController;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Restaurants Public Routes (Clients)
Route::get('/restaurants', [ClientRestaurantController::class, 'index']);
Route::get('/restaurants/{id}', [ClientRestaurantController::class, 'show']);
Route::get('/restaurants/{id}/reviews', [ReviewController::class, 'index']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Profile
    Route::get('/user', [AuthController::class, 'profile']);
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::delete('/user', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Orders (Clients)
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    
    // Admin Dashboard APIs
    Route::prefix('admin')->middleware(CheckRole::class . ':super_admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/restaurants', [AdminController::class, 'restaurantsList']);
        Route::get('/restaurants/{id}', [AdminController::class, 'restaurantDetails']);
        Route::post('/restaurants', [AdminController::class, 'createRestaurant']);
        Route::get('/users', [AdminController::class, 'usersList']);
        Route::post('/users', [AdminController::class, 'createRestaurantUser']);
    });

    // Restaurant Dashboard APIs
    Route::prefix('restaurant')->middleware(CheckRole::class . ':restaurant')->group(function () {
        Route::get('/stats', [ManagerRestaurantController::class, 'stats']);
        Route::get('/menu', [ManagerRestaurantController::class, 'menu']);
        Route::get('/reviews', [ManagerRestaurantController::class, 'reviews']);
        Route::get('/opening-hours', [ManagerRestaurantController::class, 'getOpeningHours']);
        Route::put('/opening-hours', [ManagerRestaurantController::class, 'updateOpeningHours']);
        Route::put('/info', [ManagerRestaurantController::class, 'updateInfo']);
        Route::post('/categories', [ManagerRestaurantController::class, 'createCategory']);
        Route::post('/dishes', [ManagerRestaurantController::class, 'createDish']);
        Route::get('/live-orders', [ManagerRestaurantController::class, 'liveOrders']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    });

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/toggle/{restaurantId}', [FavoriteController::class, 'toggle']);

    // Sessions
    Route::post('/sessions/start', [SessionController::class, 'start']);
    Route::get('/sessions/status', [SessionController::class, 'status']);
});
