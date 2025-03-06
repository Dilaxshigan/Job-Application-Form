<?php

use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobApplicationController::class, 'showForm'])->name('job.application.form');
Route::post('/job-application-submit', [JobApplicationController::class, 'submitForm'])->name('job.application.submit');
Route::get('/cv-data/{id}', [JobApplicationController::class, 'showCvData'])->name('cv.data');
