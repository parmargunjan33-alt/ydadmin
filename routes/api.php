<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PdfManagementController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC ROUTES (no login needed) ─────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/send-otp',               [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp',             [AuthController::class, 'verifyOtp']);
    Route::post('/register',               [AuthController::class, 'register']);
    Route::post('/login',                  [AuthController::class, 'login']);
});

Route::get('/universities',            [ContentController::class, 'universities']);
Route::get('/courses/{universityId}',  [ContentController::class, 'courses']);
Route::get('/semesters/{courseId}',    [ContentController::class, 'semesters']);
Route::get('/subjects/{semesterId}',    [ContentController::class, 'subjects']);

Route::post('/payment/webhook',        [PaymentController::class, 'webhook']);

// ── PROTECTED ROUTES (login required) ───────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout',             [AuthController::class, 'logout']);
    Route::get('/profile',             [AuthController::class, 'profile']);

    // Content
    Route::get('/my-courses',                     [ContentController::class, 'myCourses']);
    Route::get('/pdfs/{semesterId}/{subjectId}',   [ContentController::class, 'pdfList']);

    Route::get('/pdf/{pdfId}/url',     [ContentController::class, 'getPdfUrl']);

    // PDF Management
    Route::get('/pdf-management/semesters/{courseId}',     [PdfManagementController::class, 'getSemesters']);
    Route::get('/pdf-management/subjects/{semesterId}',    [PdfManagementController::class, 'getSubjects']);
    Route::post('/pdf-management/upload',                  [PdfManagementController::class, 'uploadPdfs']);
    Route::get('/pdf-management/pdfs/{semesterId}/{subjectId}', [PdfManagementController::class, 'getPdfs']);
    Route::post('/pdf-management/get-pdfs',                [PdfManagementController::class, 'getPdfsPost']);
    Route::get('/pdf-management/list',                     [PdfManagementController::class, 'listPdfs']);
    Route::put('/pdf-management/pdf/{pdfId}',              [PdfManagementController::class, 'updatePdf']);
    Route::delete('/pdf-management/pdf/{pdfId}',           [PdfManagementController::class, 'deletePdf']);

    // Payment
    Route::post('/payment/create-order',        [PaymentController::class, 'createOrder']);
    Route::post('/payment/verify',              [PaymentController::class, 'verifyPayment']);
    Route::get('/subscription/{semesterId}',    [PaymentController::class, 'checkSubscription']);
});
