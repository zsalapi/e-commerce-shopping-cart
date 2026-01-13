<?php

use App\Livewire\ProductList;
use Illuminate\Support\Facades\Route;

// Public route: Everyone can see products
Route::get('/', function () {
    return view('welcome');
});

// Protected routes: Only logged-in users can access
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::view('profile', 'profile')
        ->name('profile');
});

require __DIR__ . '/auth.php';
