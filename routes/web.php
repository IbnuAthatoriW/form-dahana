<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminTemplateController;
use App\Http\Controllers\PdfController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TemplateApprovalController;
use App\Http\Controllers\ApprovalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Portal Routes
Route::get('/', [FormController::class, 'index'])->name('home');
// Halaman sukses & PDF tetap bisa diakses
Route::get('/form/success/{code}', [FormController::class, 'success'])->name('form.success');
Route::get('/form/pdf/{submission:submission_code}', [PdfController::class, 'generatePdf'])->name('form.pdf');
// Form hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::get('/form/{template}', [FormController::class, 'fill'])->name('form.fill');
    Route::post('/form/{template}', [FormController::class, 'store'])->name('form.store');

    // Approval routes
    Route::get('/approval/{submission}', [ApprovalController::class, 'show'])->name('approval.show');
    Route::post('/approval/{submission}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{submission}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    Route::post('/approval/{submission}/revision', [ApprovalController::class, 'revision'])->name('approval.revision');
    Route::get('/submission/{submission}/timeline', [ApprovalController::class, 'timeline'])->name('submission.timeline');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Dashboard & Builder Routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminTemplateController::class, 'dashboard'])->name('admin.dashboard');

    // Templates CRUD
    Route::get('/templates', [AdminTemplateController::class, 'index'])->name('admin.templates.index');
    Route::get('/templates/create', [AdminTemplateController::class, 'create'])->name('admin.templates.create');
    Route::post('/templates', [AdminTemplateController::class, 'store'])->name('admin.templates.store');
    Route::get('/templates/{template}/edit', [AdminTemplateController::class, 'edit'])->name('admin.templates.edit');
    Route::put('/templates/{template}', [AdminTemplateController::class, 'update'])->name('admin.templates.update');
    Route::delete('/templates/{template}', [AdminTemplateController::class, 'destroy'])->name('admin.templates.destroy');

    // Sections CRUD
    Route::post('/templates/{template}/sections', [AdminTemplateController::class, 'addSection'])->name('admin.templates.sections.store');
    Route::put('/sections/{section}', [AdminTemplateController::class, 'updateSection'])->name('admin.templates.sections.update');
    Route::delete('/sections/{section}', [AdminTemplateController::class, 'destroySection'])->name('admin.templates.sections.destroy');

    // Fields CRUD
    Route::post('/sections/{section}/fields', [AdminTemplateController::class, 'addField'])->name('admin.templates.fields.store');
    Route::put('/fields/{field}', [AdminTemplateController::class, 'updateField'])->name('admin.templates.fields.update');
    Route::delete('/fields/{field}', [AdminTemplateController::class, 'destroyField'])->name('admin.templates.fields.destroy');

    // Submissions
    Route::get('/submissions', [AdminTemplateController::class, 'submissions'])->name('admin.submissions.index');
    Route::get('/submissions/{submission}', [AdminTemplateController::class, 'submissionShow'])->name('admin.submissions.show');
    Route::delete('/submissions/{submission}', [AdminTemplateController::class, 'submissionDestroy'])->name('admin.submissions.destroy');

    Route::get('/templates/{template}/workflow',[TemplateApprovalController::class, 'edit'])->name('admin.templates.workflow');
    Route::post('/templates/{template}/workflow',[TemplateApprovalController::class, 'update'])->name('admin.templates.workflow.update');
});
