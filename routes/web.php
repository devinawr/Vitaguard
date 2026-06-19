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

Route::get('/', function () {
    return view('auth.login');
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

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'index']);
});

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('/member/dashboard', [MemberController::class, 'index']);
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
