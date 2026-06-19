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

Route::get('/', fn () => redirect()->route('member.welcome'));
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
