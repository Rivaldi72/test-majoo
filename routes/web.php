<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellTransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('master')->group(function () {
        Route::middleware('can:isAdmin')->group(function () {
            Route::resource('user', UserController::class);
            Route::resource('category', ProductCategoryController::class);

            Route::post('update/product/{id}', [ProductController::class, 'update'])->name('update.product');
        });
    });
    Route::middleware('can:isHasAccess')->group(function () {
        Route::prefix('master')->group(function () {
            Route::resource('product', ProductController::class);
        });

        Route::get('data/products', [DataController::class, 'productAll'])->name('data.products');
        Route::get('data/product/{id}', [DataController::class, 'productById'])->name('data.product');
    });
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
