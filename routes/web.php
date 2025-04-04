<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', \App\Http\Controllers\ProductsController::class);

Route::resource('orders', \App\Http\Controllers\OrdersController::class);
