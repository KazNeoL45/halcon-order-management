<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/order', [App\Http\Controllers\OrderApi::class, 'show']);

use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/orders/create', [OrdersController::class, 'create'])->name('orders.create');
    Route::get('orders/deleted-list', [OrdersController::class, 'deletedOrders'])->name('orders.deleted'); 
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update'); 
    Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/{order}/mark-in-transit', [OrdersController::class, 'markInTransit'])->name('orders.markInTransit');
    Route::post('/orders/{order}/mark-delivered', [OrdersController::class, 'markDelivered'])->name('orders.markDelivered');
    Route::post('orders/{id}/restore', [OrdersController::class, 'restore'])->name('orders.restore');    
    Route::resource('clients', ClientController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\UsersController;
use App\Http\Middleware\AdminMiddleware;

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('users', UsersController::class);
});

require __DIR__.'/auth.php';
