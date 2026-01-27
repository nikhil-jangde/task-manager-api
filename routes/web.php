<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::get('/admin', [AdminController::class, 'loginForm'])->middleware('guest')->name('login');
Route::post('/admin', [AdminController::class, 'login'])->middleware('guest');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/admin/logout', [AdminController::class, 'logout'])->middleware('auth');
Route::get('/admin/api-data/{route}', [AdminController::class, 'getApiData'])->middleware('auth');
