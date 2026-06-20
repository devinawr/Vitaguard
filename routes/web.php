<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminConsultationController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConsultationMessageController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root route
|--------------------------------------------------------------------------
| Jika belum login -> tampil login
| Jika login admin -> admin dashboard
| Jika login doctor -> doctor dashboard
| Jika login member -> tampil member welcome
|
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return view('auth.login');
    }

    $user = auth()->user();

    return match ($user->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'member' => app(ArticleController::class)->welcome(),
        default  => abort(403, 'Role tidak dikenali.'),
    };
})->name('member.welcome');

Auth::routes();

/*
|--------------------------------------------------------------------------
| Member routes
|--------------------------------------------------------------------------
| URL yang diinginkan:
| localhost/                      -> member/welcome.blade.php
| localhost/artikel               -> member/articles/index.blade.php
| localhost/artikel/{slug}        -> member/articles/show.blade.php
|
*/
Route::middleware(['auth', 'role:member'])->group(function () {
    Route::prefix('artikel')->name('member.articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'publicIndex'])->name('index');
        Route::get('/{article:slug}', [ArticleController::class, 'publicShow'])->name('show');
    });

    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberController::class, 'index'])->name('dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('doctors', AdminDoctorController::class);
    Route::resource('members', AdminMemberController::class);
    Route::resource('articles', AdminArticleController::class);
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update']);
    Route::resource('consultations', AdminConsultationController::class)->only(['index', 'show']);
    Route::resource('users', AdminUserController::class);
});

/*
|--------------------------------------------------------------------------
| Doctor routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Internal resource routes
|--------------------------------------------------------------------------
| Kalau route ini memang dipakai untuk CRUD internal, minimal lindungi dengan auth.
| Kalau ternyata tidak dipakai, lebih baik hapus sekalian.
|
*/
Route::middleware(['auth'])->group(function () {
    Route::resources([
        'users' => UserController::class,
        'doctors' => DoctorController::class,
        'members' => MemberController::class,
        'articles' => ArticleController::class,
        'doctor-schedules' => DoctorScheduleController::class,
        'bookings' => BookingController::class,
        'consultations' => ConsultationController::class,
        'consultation-messages' => ConsultationMessageController::class,
    ]);
});

/*
|--------------------------------------------------------------------------
| /home route
|--------------------------------------------------------------------------
| Kalau ada redirect lama ke /home, arahkan saja ke root.
|
*/
Route::get('/home', function () {
    return redirect()->route('member.welcome');
})->name('home');