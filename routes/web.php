<?php

use App\Http\Controllers\HomeProductController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeProductController::class, 'index']);

Route::post('/products', [HomeProductController::class, 'store']);

Route::get('/products', [HomeProductController::class, 'getProducts']);

Route::put('/products/{id}', [HomeProductController::class, 'update']);
