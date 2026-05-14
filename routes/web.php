<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/demo-disclosure', 'pages.legal')->name('pages.legal');

// Placeholders until the matching feature stages land.
Route::view('/properties', 'pages.coming-soon')->name('properties.index');
Route::view('/tools/emi', 'pages.coming-soon')->name('tools.emi');
Route::view('/tools/investment', 'pages.coming-soon')->name('tools.investment');
Route::view('/compare', 'pages.coming-soon')->name('compare.index');
Route::view('/admin/login', 'pages.coming-soon')->name('admin.login');
Route::view('/forgot-password', 'pages.coming-soon')->name('password.request');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::view('/wishlist', 'pages.coming-soon')->name('wishlist.index');
    Route::view('/appointments', 'pages.coming-soon')->name('appointments.index');
    Route::view('/profile', 'pages.coming-soon')->name('profile.edit');
});
