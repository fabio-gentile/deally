<?php

use App\Http\Controllers\DealController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\BreezeProfileController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
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

// Search
Route::get('/rechercher/deals', [\App\Http\Controllers\SearchController::class, 'searchDeal'])->name('search.deals');
Route::get('/rechercher/discussions', [\App\Http\Controllers\SearchController::class, 'searchDiscussion'])->name('search.discussions');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [BreezeProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [BreezeProfileController::class, 'destroy'])->name('profile.destroy');
});

//Profile
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profil/{user}', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profil/{user}/favoris', [ProfileController::class, 'index'])->name('profile.favorite');
    Route::get('/profil/{user}/deals', [ProfileController::class, 'deals'])->name('profile.deals');
    Route::get('/profil/{user}/discussions', [ProfileController::class, 'discussions'])->name('profile.discussions');
    Route::get('/profil/{user}/newsletter', [ProfileController::class, 'newsletter'])->name('profile.newsletter');
    Route::get('/profil/{user}/statistiques', [ProfileController::class, 'statistics'])->name('profile.statistics');
    Route::get('/profil/{user}/parametres', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profil/{user}/parametres', [ProfileController::class, 'updateProfileInformations'])->name('profile.update.profile.informations')->middleware([HandlePrecognitiveRequests::class]);
    Route::patch('/profil', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [BreezeProfileController::class, 'destroy'])->name('profile.destroy');
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
    Route::post('/deals/vote/{id}', [VoteController::class, 'store'])->name('deals.vote.store');
//    Route::delete('/deals/{deal}/vote', [DealController::class, 'unvote'])->name('deals.unvote');
});

// favorite
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('favorite', [\App\Http\Controllers\FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('favorite', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorite.destroy');
});


require __DIR__.'/auth.php';
