<?php

use App\Http\Controllers\DealController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;
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

//Deals
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create');
    Route::post('/deals/create', [DealController::class, 'store'])->name('deals.store');
    Route::get('/deals/{slug}/edit', [DealController::class, 'edit'])->name('deals.edit');
    Route::patch('/deals/{id}', [DealController::class, 'update'])->name('deals.update');
    Route::delete('/deals/{id}', [DealController::class, 'destroy'])->name('deals.destroy');
    Route::delete('/deals/image/{filename}', [DealController::class, 'deleteImage'])->name('deals.delete-image');

//    comments
    Route::post('/deals/{slug}/comments', [\App\Http\Controllers\CommentDealController::class, 'store'])->name('deals.comments.store');
    Route::delete('/deals/comments/{comment}', [\App\Http\Controllers\CommentDealController::class, 'destroy'])->name('deals.comments.destroy');
});

Route::get('/deals/{slug}', [DealController::class, 'show'])->name('deals.show');

//Discussion
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/discussions/create', [DiscussionController::class, 'create'])->name('discussions.create');
    Route::post('/discussions/create', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::get('/discussions/{slug}/edit', [DiscussionController::class, 'edit'])->name('discussions.edit');
    Route::patch('/discussions/{id}', [DiscussionController::class, 'update'])->name('discussions.update');
    Route::delete('/discussions/{id}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

//comments
    Route::post('/discussions/{slug}/comments', [\App\Http\Controllers\CommentDiscussionController::class, 'store'])->name('discussions.comments.store');
    Route::delete('/discussions/comments/{id}', [\App\Http\Controllers\CommentDiscussionController::class, 'destroy'])->name('discussions.comments.destroy');
});

Route::get('/discussions/{slug}', [DiscussionController::class, 'show'])->name('discussions.show');



//Vote
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/deals/{deal}/vote', [VoteController::class, 'store'])->name('deals.vote.store');
//    Route::delete('/deals/{deal}/vote', [DealController::class, 'unvote'])->name('deals.unvote');
});

require __DIR__.'/auth.php';
