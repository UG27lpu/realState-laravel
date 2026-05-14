<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/demo-disclosure', 'pages.legal')->name('pages.legal');

/*
 * The following named routes are placeholders kept here so shared blade
 * partials (navbar, footer, components) resolve cleanly from the very first
 * commit. Each one is replaced with a real controller-backed route as the
 * matching feature stage is implemented.
 */
Route::view('/properties', 'pages.coming-soon')->name('properties.index');
Route::view('/tools/emi', 'pages.coming-soon')->name('tools.emi');
Route::view('/tools/investment', 'pages.coming-soon')->name('tools.investment');
Route::view('/login', 'pages.coming-soon')->name('login');
Route::view('/register', 'pages.coming-soon')->name('register');
Route::view('/dashboard', 'pages.coming-soon')->name('dashboard');
Route::view('/admin', 'pages.coming-soon')->name('admin.dashboard');
Route::view('/admin/login', 'pages.coming-soon')->name('admin.login');
Route::view('/wishlist', 'pages.coming-soon')->name('wishlist.index');
Route::view('/appointments', 'pages.coming-soon')->name('appointments.index');
Route::view('/profile', 'pages.coming-soon')->name('profile.edit');
Route::post('/logout', fn () => redirect()->route('home'))->name('logout');
