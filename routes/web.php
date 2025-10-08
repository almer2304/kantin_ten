<?php

use App\Http\Controllers\ProductKantinController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductKantinController::class, 'index'])->name('dashboard');
Route::post('/', [ProductKantinController::class, 'store'])->name('store_menu');
Route::put('/menu/{produk}', [ProductKantinController::class, 'update'])->name('update_menu');
Route::delete('/menu/{produk}', [ProductKantinController::class, 'destroy'])->name('delete_menu');
Route::post('/menu/{produk}/beli', [ProductKantinController::class, 'beli'])->name('beli_menu');

