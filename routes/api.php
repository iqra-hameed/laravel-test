<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

// User Profile Routes
Route::middleware('auth:api')->group(function () {
    // Direct profile routes (for easier access)
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    Route::put('password', [UserController::class, 'updatePassword']);
    
    // Current user profile routes (alternative paths)
    Route::prefix('user')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('password', [UserController::class, 'updatePassword']);
    });
    
    // Admin routes (protected by admin middleware)
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
    });
});

// Dashboard route (protected)
Route::middleware('auth:api')->get('/dashboard', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'success' => true,
        'message' => 'Welcome to your dashboard!',
        'data' => [
            'user' => $user,
            'dashboard_stats' => [
                'total_users' => $user->isAdmin() ? \App\Models\User::count() : null,
                'user_role' => $user->role,
                'last_login' => now(),
            ]
        ]
    ]);
});
