<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanApplicationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublicationReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Api\PublicApiController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureSingleAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/pengajuan-kredit', [PublicApiController::class, 'submitLoan'])->name('pengajuan.submit');
Route::get('/captcha/generate', [PublicApiController::class, 'captcha'])->name('captcha.generate');
Route::post('/pengaduan', [PublicApiController::class, 'submitComplaint'])->name('pengaduan.submit');
Route::get('/pengaduan/status', [PublicApiController::class, 'checkComplaintStatus'])->name('pengaduan.status');
Route::post('/pengaduan/status', [PublicApiController::class, 'checkComplaintStatus'])->name('pengaduan.status.post');

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

    Route::middleware(['auth', EnsureSingleAdmin::class])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Customer Complaints Management (Pengaduan Nasabah & Anti-Fraud)
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
        Route::put('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
        Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

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

        // Admin Profile & Change Password
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
