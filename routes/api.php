<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticlePreferenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('user/preferences', [UserPreferenceController::class, 'show'])->name('preferences.show');
    Route::get('user/preferences/edit', [UserPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::post('user/preferences', [UserPreferenceController::class, 'update'])->name('preferences.store');
    Route::get('articles/prefered', ArticlePreferenceController::class)->name('prefered.articles');
});

Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
