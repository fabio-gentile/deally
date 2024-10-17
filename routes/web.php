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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pour-vous', [HomeController::class, 'forYou'])
        ->name('home.for-you');
});

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
Route::get('/profil/{user}', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profil/{user}/favoris', [ProfileController::class, 'index'])->name('profile.favorite');
Route::get('/profil/{user}/deals', [ProfileController::class, 'deals'])->name('profile.deals');
Route::get('/profil/{user}/discussions', [ProfileController::class, 'discussions'])->name('profile.discussions');
Route::get('/profil/{user}/statistiques', [ProfileController::class, 'statistics'])->name('profile.statistics');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profil/{user}/newsletter', [ProfileController::class, 'newsletter'])->name('profile.newsletter');
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

// Report
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('report', [\App\Http\Controllers\ReportController::class, 'store'])->name('report.store');
});

// Admin
Route::middleware(['guest'])->prefix('admin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthenticatedSessionController::class, 'create'])->name('admin.login.create');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthenticatedSessionController::class, 'login'])->name('admin.login.store');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthenticatedSessionController::class, 'destroy'])
        ->name('admin.login.logout');
});

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashbordController::class, 'index'])->name('admin.dashboard');

    // Users
    Route::get('/utilisateurs', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin.users.list');
    Route::get('/utilisateurs/{id}/edit', [\App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/utilisateurs/{id}/edit', [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('admin.users.update')->middleware([HandlePrecognitiveRequests::class]);

    // Discussion
    Route::get('/discussions', [\App\Http\Controllers\Admin\AdminDiscussionController::class, 'index'])->name('admin.discussions.list');
    Route::get('/discussions/{id}/edit', [\App\Http\Controllers\Admin\AdminDiscussionController::class, 'edit'])->name('admin.discussions.edit');
    Route::patch('/discussions/{id}/edit', [\App\Http\Controllers\Admin\AdminDiscussionController::class, 'update'])->name('admin.discussions.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/discussions/{id}', [\App\Http\Controllers\Admin\AdminDiscussionController::class, 'destroy'])->name('admin.discussions.destroy');

    // Discussion Comments
    Route::get('/discussions/{id}/comments', [\App\Http\Controllers\Admin\AdminCommentDiscussionController::class, 'index'])->name('admin.discussions.comments.list');
    Route::delete('/discussions/comments/{id}', [\App\Http\Controllers\Admin\AdminCommentDiscussionController::class, 'destroy'])->name('admin.discussions.comments.destroy');
    Route::get('/discussions/comments/{id}', [\App\Http\Controllers\Admin\AdminCommentDiscussionController::class, 'show'])->name('admin.discussions.comments.show');

    // Category Discussion
    Route::get('/categories-discussions', [\App\Http\Controllers\Admin\AdminCategoryDiscussionController::class, 'index'])->name('admin.categories-discussions.list');
    Route::get('/categories-discussions/create', [\App\Http\Controllers\Admin\AdminCategoryDiscussionController::class, 'create'])->name('admin.categories-discussions.create');
    Route::post('/categories-discussions/create', [\App\Http\Controllers\Admin\AdminCategoryDiscussionController::class, 'store'])->name('admin.categories-discussions.store');
    Route::get('/categories-discussions/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryDiscussionController::class, 'edit'])->name('admin.categories-discussions.edit');
    Route::patch('/categories-discussions/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryDiscussionController::class, 'update'])->name('admin.categories-discussions.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/categories-discussions/{id}', [\App\Http\Controllers\Admin\AdminCategoryDiscussionController::class, 'destroy'])->name('admin.categories-discussions.destroy');

    // Deals
    Route::get('/deals', [\App\Http\Controllers\Admin\AdminDealController::class, 'index'])->name('admin.deals.list');
    Route::get('/deals/{id}/edit', [\App\Http\Controllers\Admin\AdminDealController::class, 'edit'])->name('admin.deals.edit');
    Route::patch('/deals/{id}/edit', [\App\Http\Controllers\Admin\AdminDealController::class, 'update'])->name('admin.deals.update');
    Route::delete('/deals/{id}', [\App\Http\Controllers\Admin\AdminDealController::class, 'destroy'])->name('admin.deals.destroy');
    Route::delete('/deals/image/{filename}', [\App\Http\Controllers\Admin\AdminDealController::class, 'deleteImage'])->name('admin.deals.delete-image');

    // Deal Comments
    Route::get('/deals/{id}/comments', [\App\Http\Controllers\Admin\AdminCommentDealController::class, 'index'])->name('admin.deal.comments.list');
    Route::delete('/deals/comments/{id}', [\App\Http\Controllers\Admin\AdminCommentDealController::class, 'destroy'])->name('admin.deal.comments.destroy');
    Route::get('/deals/comments/{id}', [\App\Http\Controllers\Admin\AdminCommentDealController::class, 'show'])->name('admin.deal.comments.show');

    // Category Deal
    Route::get('/categories-deals', [\App\Http\Controllers\Admin\AdminCategoryDealController::class, 'index'])->name('admin.categories-deals.list');
    Route::get('/categories-deals/create', [\App\Http\Controllers\Admin\AdminCategoryDealController::class, 'create'])->name('admin.categories-deals.create');
    Route::post('/categories-deals/create', [\App\Http\Controllers\Admin\AdminCategoryDealController::class, 'store'])->name('admin.categories-deals.store');
    Route::get('/categories-deals/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryDealController::class, 'edit'])->name('admin.categories-deals.edit');
    Route::patch('/categories-deals/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryDealController::class, 'update'])->name('admin.categories-deals.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/categories-deals/{id}', [\App\Http\Controllers\Admin\AdminCategoryDealController::class, 'destroy'])->name('admin.categories-deals.destroy');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('admin.reports.list');
    Route::get('/reports/{id}', [\App\Http\Controllers\Admin\AdminReportController::class, 'show'])->name('admin.reports.show');
    Route::delete('/reports/{id}', [\App\Http\Controllers\Admin\AdminReportController::class, 'destroy'])->name('admin.reports.destroy');
});



require __DIR__.'/auth.php';
