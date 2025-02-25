<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;

use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('user/preferences', UserPreferenceController::class)->name('preferences.store');
});
