<?php

use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobApplicationController::class, 'showForm'])->name('job.application.form');
Route::post('/job-application-submit', [JobApplicationController::class, 'submitForm'])->name('job.application.submit');
