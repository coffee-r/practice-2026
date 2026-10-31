<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SellerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// TOPページはダッシュボードにリダイレクト
Route::redirect('/', '/dashboard');

// ログインフォーム
Route::get('/login', [LoginController::class, 'show'])->name('login.show');

// ログイン
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    // ログアウト
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    // 出品者一覧
    Route::get('/sellers', [SellerController::class, 'index'])->name('sellers');

    // 出品者編集
    Route::get('/sellers/{seller:id}', [SellerController::class, 'edit'])->name('seller.edit');

    // 出品者更新
    Route::post('/sellers/{seller:id}', [SellerController::class, 'update'])->name('seller.update');
});
