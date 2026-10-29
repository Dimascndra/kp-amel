<?php

use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [PublicApiController::class, 'products']);
    Route::get('/products/{slug}', [PublicApiController::class, 'productDetail']);
    Route::get('/branches', [PublicApiController::class, 'branches']);
    Route::get('/articles', [PublicApiController::class, 'articles']);
    Route::get('/articles/{slug}', [PublicApiController::class, 'articleDetail']);
    Route::get('/reports', [PublicApiController::class, 'reports']);
    Route::get('/settings', [PublicApiController::class, 'settings']);
    Route::get('/search', [PublicApiController::class, 'search']);
    Route::post('/pengajuan-kredit', [PublicApiController::class, 'submitLoan']);
});

// Non-versioned alias routes for direct frontend consumption
Route::get('/products', [PublicApiController::class, 'products']);
Route::get('/products/{slug}', [PublicApiController::class, 'productDetail']);
Route::get('/branches', [PublicApiController::class, 'branches']);
Route::get('/articles', [PublicApiController::class, 'articles']);
Route::get('/articles/{slug}', [PublicApiController::class, 'articleDetail']);
Route::get('/reports', [PublicApiController::class, 'reports']);
Route::get('/settings', [PublicApiController::class, 'settings']);
Route::get('/search', [PublicApiController::class, 'search']);
Route::post('/pengajuan-kredit', [PublicApiController::class, 'submitLoan']);
