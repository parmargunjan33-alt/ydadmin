<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\AppConfigController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Api\ContentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pdf/serve/{pdf}', [ContentController::class, 'servePdf'])
    ->name('pdf.serve');

// Admin Authentication Routes (Guest Only)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Panel Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource Management
    Route::resource('universities', UniversityController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('users', UserController::class);
    Route::resource('subscriptions', SubscriptionController::class)->only(['index', 'show', 'destroy']);
    Route::resource('config', AppConfigController::class)->only(['index', 'edit', 'update']);
    Route::resource('admin-users', AdminUserController::class);
    Route::resource('pdfs', PdfController::class);
});
