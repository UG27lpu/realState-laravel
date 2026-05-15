<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/demo-disclosure', 'pages.legal')->name('pages.legal');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::view('/tools/emi', 'pages.coming-soon')->name('tools.emi');
Route::view('/tools/investment', 'pages.coming-soon')->name('tools.investment');
Route::view('/compare', 'pages.coming-soon')->name('compare.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/me/properties', [PropertyController::class, 'mine'])->name('properties.mine');
    Route::get('/properties-new', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property:slug}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property:slug}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property:slug}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::delete('/property-images/{image}', [PropertyController::class, 'deleteImage'])->name('properties.images.destroy');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{property:slug}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::view('/appointments', 'pages.coming-soon')->name('appointments.index');
    Route::view('/profile', 'pages.coming-soon')->name('profile.edit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
});
