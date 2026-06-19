<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConsultationMessageController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminConsultationController;
use App\Http\Controllers\Admin\AdminUserController;

// Route::get('/', fn () => redirect()->route('member.welcome'));
Route::get('/', function () {return view('auth.login');});
Route::get('/welcome', [ArticleController::class, 'welcome'])->name('member.welcome');

Route::prefix('artikel')->name('member.articles.')->group(function () {
    Route::get('/', [ArticleController::class, 'publicIndex'])->name('index');
    Route::get('/{article:slug}', [ArticleController::class, 'publicShow'])->name('show');
});


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
Auth::routes();

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Doctor Management
    Route::resource('doctors', AdminDoctorController::class);
    
    // Member/Patient Management
    Route::resource('members', AdminMemberController::class);
    
    // Article Management
    Route::resource('articles', AdminArticleController::class);
    
    // Booking Management
    Route::resource('bookings', AdminBookingController::class, ['only' => ['index', 'show', 'update']]);
    
    // Consultation Management
    Route::resource('consultations', AdminConsultationController::class, ['only' => ['index', 'show']]);
    
    // User Account Management
    Route::resource('users', AdminUserController::class);
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
});

Route::middleware(['auth', 'role:member'])->prefix('member')->group(function () {
    Route::get('/dashboard', [MemberController::class, 'index'])->name('member.dashboard');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
