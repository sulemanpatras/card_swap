<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\CardController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\AdminController;

Route::prefix('users')->group(function () {
    Route::post('create', [UserController::class, 'register']);
    Route::post('verify-otp', [UserController::class, 'verifyOtp']);   
    Route::post('login', [UserController::class, 'login']);   
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);
    Route::post('register', [AdminController::class, 'register']);  
});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

   
    Route::prefix('reset-password')->group(function () {
        Route::post('request-otp', [AdminController::class, 'requestOtp']);
        Route::post('reset-password', [AdminController::class, 'resetPassword']);
        
    });

    Route::prefix('admin')->group(function () {
        Route::post('update', [AdminController::class, 'update']);
        Route::post('/cards/status', [AdminController::class, 'updateStatus']);
        Route::post('/users/status', [AdminController::class, 'updateUserStatus']);
    });

    Route::prefix('role')->group(function () {
        Route::get('roles', [RoleController::class, 'index']);
        Route::post('roles-store', [RoleController::class, 'store']); 
        Route::post('roles-update', [RoleController::class, 'update']); 
        Route::delete('roles-delete', [RoleController::class, 'destroy']); 
        });
        
        
        Route::prefix('permission')->group(function () {
        Route::get('permissions', [PermissionController::class, 'index']); 
        Route::post('permissions-store', [PermissionController::class, 'store']);
        Route::post('permissions-update', [PermissionController::class, 'update']); 
        Route::delete('permissions-delete', [PermissionController::class, 'destroy']); 
        });
    
        Route::prefix('assign-role')->group(function () {
            Route::post('roles/permissions', [RoleController::class, 'assignPermissions']);
        });

    Route::prefix('change-password')->group(function () {
        Route::post('change-password', [AdminController::class, 'changePassword']);        
    });


    Route::prefix('dashboard')->group(function () {
        Route::get('getDashboardStats', [DashboardController::class, 'getDashboardStats']);
    });

    Route::prefix('card')->group(function () {
        Route::get('user/cards', [CardController::class, 'getCards']);
    });

   
        
        Route::prefix('users')->group(function () {
            Route::get('get', [UserController::class, 'index']);
        });

});

Route::middleware('auth:sanctum')->group(function () {

   
    Route::prefix('users')->group(function () {
    Route::post('/edit', [UserController::class, 'edit']);
    });


    Route::prefix('card')->group(function () { 
        Route::post('store', [CardController::class, 'store']);
        Route::post('update', [CardController::class, 'update']);
        Route::post('usercardupdate', [CardController::class, 'usercardupdate']);
        Route::get('/getWebsites', [CardController::class, 'getWebsites']);

    });
    
    Route::prefix('dashboard')->group(function () {
        Route::get('get', [DashboardController::class, 'get']);
        Route::get('getdash', [DashboardController::class, 'getdash']);
        Route::get('getdashbyid/{id}', [DashboardController::class, 'getDashById']);

    });

});

