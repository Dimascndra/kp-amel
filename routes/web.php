<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanApplicationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PublicationReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Api\PublicApiController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/pengajuan-kredit', [PublicApiController::class, 'submitLoan'])->name('pengajuan.submit');

// Fallback alias for default auth redirect
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Products CRUD
        Route::patch('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::resource('products', ProductController::class);

        // Branches CRUD
        Route::patch('/branches/{branch}/toggle', [BranchController::class, 'toggleStatus'])->name('branches.toggle');
        Route::resource('branches', BranchController::class);

        // Articles CRUD
        Route::patch('/articles/{article}/toggle', [ArticleController::class, 'toggleStatus'])->name('articles.toggle');
        Route::resource('articles', ArticleController::class);

        // Publication Reports CRUD
        Route::patch('/reports/{report}/toggle', [PublicationReportController::class, 'toggleStatus'])->name('reports.toggle');
        Route::resource('reports', PublicationReportController::class);

        // Loan Applications Management
        Route::get('/loans', [LoanApplicationController::class, 'index'])->name('loans.index');
        Route::get('/loans/{loan}', [LoanApplicationController::class, 'show'])->name('loans.show');
        Route::put('/loans/{loan}/status', [LoanApplicationController::class, 'updateStatus'])->name('loans.updateStatus');
        Route::delete('/loans/{loan}', [LoanApplicationController::class, 'destroy'])->name('loans.destroy');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
