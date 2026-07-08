<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminTemplateController;
use App\Http\Controllers\PdfController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Portal Routes
Route::get('/', [FormController::class, 'index'])->name('home');
Route::get('/form/{template}', [FormController::class, 'fill'])->name('form.fill');
Route::post('/form/{template}', [FormController::class, 'store'])->name('form.store');
Route::get('/form/success/{code}', [FormController::class, 'success'])->name('form.success');
Route::get('/form/pdf/{submission:submission_code}', [PdfController::class, 'generatePdf'])->name('form.pdf');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
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
});
