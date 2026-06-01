<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController       as AdminDashboard;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AlumniApprovalController;
use App\Http\Controllers\Admin\ActivityMonitorController;
use App\Http\Controllers\Alumni\DashboardController      as AlumniDashboard;
use App\Http\Controllers\Alumni\ProfileController        as AlumniProfileController;
use App\Http\Controllers\Alumni\MentorshipController     as AlumniMentorshipController;
use App\Http\Controllers\Student\DashboardController     as StudentDashboard;
use App\Http\Controllers\Student\AlumniSearchController;
use App\Http\Controllers\Student\MentorshipRequestController;
use App\Http\Controllers\Student\ProfileController       as StudentProfileController;
use Illuminate\Support\Facades\Route;

// ── Root redirect ──────────────────────────────────────────────────────────
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return match (auth()->user()->role) {
        'admin'   => redirect()->route('admin.home'),
        'alumni'  => redirect()->route('alumni.home'),
        'student' => redirect()->route('student.home'),
        default   => redirect()->route('login'),
    };
});

// ── Guest-only routes ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',   [LoginController::class,    'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Admin routes ───────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('home');

        // ── Alumni approval ────────────────────────────────────────────────
        Route::get('/alumni',
            [AlumniApprovalController::class, 'index'])->name('alumni.index');
        Route::patch('/alumni/{alumni}/approve',
            [AlumniApprovalController::class, 'approve'])->name('alumni.approve');
        Route::patch('/alumni/{alumni}/reject',
            [AlumniApprovalController::class, 'reject'])->name('alumni.reject');

        // ── User management ────────────────────────────────────────────────
        Route::get('/users',
            [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',
            [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit',
            [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',
            [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-active',
            [UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
        Route::patch('/users/{user}/reset-password',
            [UserManagementController::class, 'resetPassword'])->name('users.reset-password');

        // ── Mentorship monitor ─────────────────────────────────────────────
        Route::get('/mentorship',
            [ActivityMonitorController::class, 'index'])->name('mentorship.index');
    });

// ── Alumni routes ──────────────────────────────────────────────────────────
Route::prefix('alumni')
    ->name('alumni.')
    ->middleware(['auth', 'role:alumni', 'alumni.approved'])
    ->group(function () {

        Route::get('/dashboard', [AlumniDashboard::class, 'index'])->name('home');

        // Profile
        Route::get('/profile',  [AlumniProfileController::class, 'edit'])  ->name('profile');
        Route::put('/profile',  [AlumniProfileController::class, 'update'])->name('profile.update');

        // Mentorship requests received
        Route::get('/requests',                         [AlumniMentorshipController::class, 'index']) ->name('requests.index');
        Route::get('/requests/{request}',               [AlumniMentorshipController::class, 'show'])  ->name('requests.show');
        Route::patch('/requests/{request}/accept',      [AlumniMentorshipController::class, 'accept'])->name('requests.accept');
        Route::patch('/requests/{request}/reject',      [AlumniMentorshipController::class, 'reject'])->name('requests.reject');
    });

// ── Student routes ─────────────────────────────────────────────────────────
Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'role:student'])
    ->group(function () {

        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('home');

        // Student profile (for career interests)
        Route::get('/profile',  [StudentProfileController::class, 'edit'])  ->name('profile.edit');
        Route::put('/profile',  [StudentProfileController::class, 'update'])->name('profile.update');

        // Alumni search & browse
        Route::get('/alumni',           [AlumniSearchController::class, 'index'])->name('alumni.index');
        Route::get('/alumni/{alumni}',  [AlumniSearchController::class, 'show']) ->name('alumni.show');

        // Mentorship requests sent
        // IMPORTANT: named routes with static segments must come before dynamic {request}
        Route::get('/requests',                         [MentorshipRequestController::class, 'index']) ->name('requests.index');
        Route::get('/requests/create/{alumni}',         [MentorshipRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests/{alumni}',               [MentorshipRequestController::class, 'store']) ->name('requests.store');
        Route::get('/requests/{request}',               [MentorshipRequestController::class, 'show'])  ->name('requests.show');
    });