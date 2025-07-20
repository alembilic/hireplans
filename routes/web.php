<?php

use App\Http\Controllers\JobController;
use App\Orchid\Screens\Reference\PublicReferenceFeedbackEditScreen;
use App\Livewire\AboutPage;
use App\Livewire\TermsPage;
use App\Livewire\PrivacyPage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Jobs\JobDetails;
// Schedule is now handled by Orchid platform
use App\Models\Job;

// Route::view('/', 'welcome');
Route::get('/', HomePage::class)->name('home');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/terms-of-use', TermsPage::class)->name('terms-of-use');
Route::get('/privacy-policy', PrivacyPage::class)->name('privacy-policy');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::get('dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

// Redefine the route for public access
Route::screen('feedback/{reference?}/edit', PublicReferenceFeedbackEditScreen::class)->name('feedback.edit');

require __DIR__.'/auth.php';

Route::get('/jobs/listings', function () {
    $page = request()->get('page', 1);
    session(['page' => $page]);

    return view('job-listings', ['page' => $page]);
})->name('jobs.listings');

// Route::get('/jobs/{id}', function ($id) {
//     $job = Job::findOrFail($id);
//     return view('job-details', ['job' => $job]);
// })->name('jobs.details');
Route::get('/jobs/details/{id}', JobDetails::class)->name('jobs.details');

// Route::get('/jobs/{id}', 'JobController@showDetails')->name('jobs.details');
// Route::get('/jobs/{id}', [JobController::class, 'showDetails'])->name('jobs.details');
