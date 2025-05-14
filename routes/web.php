<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

// Constrain route parameters to avoid conflicts (e.g., 'deleted-list')
Route::pattern('order', '[0-9]+');
Route::pattern('id', '[0-9]+');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/order', [App\Http\Controllers\OrderApi::class, 'show']);

use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Orders Routes
// Create orders (Admin and Sales)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Sales'])->group(function () {
    Route::get('/orders/create', [OrdersController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
});
// Read orders (Admin, Sales, Route, Warehouse)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Sales,Route,Warehouse'])->group(function () {
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
});
// Update orders (Admin, Route, Warehouse)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Route,Warehouse'])->group(function () {
    Route::get('/orders/{order}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update');
});
// Delete and restore orders (Admin)
Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
    Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');
    Route::post('orders/{id}/restore', [OrdersController::class, 'restore'])->name('orders.restore');
    Route::get('orders/deleted-list', [OrdersController::class, 'deletedOrders'])->name('orders.deleted');
    Route::post('/orders/{order}/mark-in-transit', [OrdersController::class, 'markInTransit'])->name('orders.markInTransit');
    Route::post('/orders/{order}/mark-in-process', [OrdersController::class, 'markInProcess'])->name('orders.markInProcess');
    Route::post('/orders/{order}/mark-delivered', [OrdersController::class, 'markDelivered'])->name('orders.markDelivered');
    Route::post('orders/{id}/restore', [OrdersController::class, 'restore'])->name('orders.restore');
    Route::resource('clients', ClientController::class);
});

// Clients Routes
// Create clients (Admin and Sales)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Sales'])->group(function () {
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
});
// Manage clients (Admin)
Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});

// Products Routes
// Read products (Admin, Sales, Route, Warehouse)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Sales,Route,Warehouse'])->group(function () {
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
});
// Create products (Admin and Purchaser)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Purchaser'])->group(function () {
    Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
});
// Update products (Admin and Warehouse)
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Warehouse'])->group(function () {
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
});
// Delete products (Admin)
Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
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
