<?php
use Illuminate\Support\Facades\Auth;
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

    Route::middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->group(function () {
        Route::get('/admin/products', \App\Livewire\Admin\Products::class)->name('admin.products');
    });
});

require __DIR__ . '/auth.php';
