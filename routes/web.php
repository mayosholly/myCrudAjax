<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [\App\Http\Controllers\PostController::class, 'index']);
Route::get('/fetchAll', [\App\Http\Controllers\PostController::class, 'fetchAll'])->name('fetchAll');
Route::post('/store', [\App\Http\Controllers\PostController::class, 'store'])->name('store');
Route::get('/edit', [\App\Http\Controllers\PostController::class, 'edit'])->name('edit');
Route::post('/update', [\App\Http\Controllers\PostController::class, 'update'])->name('update');
Route::delete('/destroy', [\App\Http\Controllers\PostController::class, 'destroy'])->name('destroy');
