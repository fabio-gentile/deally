<?php

use App\Http\Controllers\DealController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])
    ->name('home.index');

Route::get('/pour-vous', [HomeController::class, 'forYou'])
    ->name('home.for-you');

Route::get('/nouveaux', [HomeController::class, 'new'])
    ->name('home.new');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create');
    Route::post('/deals/create', [DealController::class, 'store'])->name('deals.store');
    Route::get('/deals/{deal}/edit', [DealController::class, 'edit'])->name('deals.edit');
    Route::patch('/deals/{deal}', [DealController::class, 'update'])->name('deals.update');
    Route::delete('/deals/{deal}', [DealController::class, 'destroy'])->name('deals.destroy');
});

require __DIR__.'/auth.php';
