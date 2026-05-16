<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Demo\SmartPropertyController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/demo-disclosure', 'pages.legal')->name('pages.legal');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

// Compare works for guests via session.
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/{property:slug}', [CompareController::class, 'add'])->name('compare.add');
Route::post('/compare/{property:slug}/remove', [CompareController::class, 'remove'])->name('compare.remove');
Route::post('/compare', [CompareController::class, 'clear'])->name('compare.clear');

Route::view('/tools/emi', 'pages.coming-soon')->name('tools.emi');
Route::view('/tools/investment', 'pages.coming-soon')->name('tools.investment');

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

    // Chat.
    Route::get('/messages', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/messages/start/{property:slug}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/messages/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/messages/{conversation}', [ChatController::class, 'send'])->name('chat.send');

    // Smart demo endpoints — JSON.
    Route::post('/smart/describe',  [SmartPropertyController::class, 'describe'])->name('smart.describe');
    Route::post('/smart/price',     [SmartPropertyController::class, 'predictPrice'])->name('smart.price');
    Route::post('/smart/duplicate', [SmartPropertyController::class, 'checkDuplicate'])->name('smart.duplicate');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/properties/{property:slug}/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');

    Route::view('/profile', 'pages.coming-soon')->name('profile.edit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
});
