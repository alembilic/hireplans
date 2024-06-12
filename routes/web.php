<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Jobs\JobDetails;
use App\Models\Job;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::get('dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

require __DIR__.'/auth.php';

Route::get('/jobs', function () {
    return view('job-listings');
})->name('jobs.listings');

// Route::get('/jobs/{id}', function ($id) {
//     $job = Job::findOrFail($id);
//     return view('job-details', ['job' => $job]);
// })->name('jobs.details');
Route::get('/jobs/{id}', JobDetails::class)->name('jobs.details');

// Route::get('/jobs/{id}', 'JobController@showDetails')->name('jobs.details');
// Route::get('/jobs/{id}', [JobController::class, 'showDetails'])->name('jobs.details');
