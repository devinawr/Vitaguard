<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminDoctorScheduleController;
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
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorBookingController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\Member\MemberBookingController;
use App\Http\Controllers\Member\MemberDoctorController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::prefix('artikel')->name('member.articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'publicIndex'])->name('index');
        Route::get('/{article:slug}', [ArticleController::class, 'publicShow'])->name('show');
    });

    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberController::class, 'index'])->name('dashboard');
    });

    Route::prefix('dokter')->name('member.doctors.')->group(function () {
        Route::get('/', [MemberDoctorController::class, 'index'])->name('index');
        Route::get('/{doctor}', [MemberDoctorController::class, 'show'])->name('show');
    });

    Route::prefix('booking')->name('member.bookings.')->group(function () {
        Route::get('/', [MemberBookingController::class, 'index'])->name('index');
        Route::get('/buat/{doctor}', [MemberBookingController::class, 'create'])->name('create');
        Route::post('/', [MemberBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [MemberBookingController::class, 'show'])->name('show');
        Route::delete('/{booking}', [MemberBookingController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('doctors', AdminDoctorController::class);
    Route::post('doctors/{doctor}/schedules', [AdminDoctorScheduleController::class, 'store'])->name('doctors.schedules.store');
    Route::delete('doctors/{doctor}/schedules/{schedule}', [AdminDoctorScheduleController::class, 'destroy'])->name('doctors.schedules.destroy');
    Route::resource('members', AdminMemberController::class);
    Route::resource('articles', AdminArticleController::class);
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update']);
    Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::resource('consultations', AdminConsultationController::class)->only(['index', 'show']);
    Route::resource('users', AdminUserController::class);
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('booking')->name('bookings.')->group(function () {
        Route::get('/', [DoctorBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [DoctorBookingController::class, 'show'])->name('show');
    });
});

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

Route::get('/home', function () {
    return redirect()->route('member.welcome');
})->name('home');
