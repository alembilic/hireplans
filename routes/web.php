<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->route('home');
});

// ...existing routes...

// Privacy and Terms pages
Route::get('/privacy-policy', \App\Livewire\PrivacyPage::class)->name('privacy-policy');
Route::get('/terms-of-use', \App\Livewire\TermsPage::class)->name('terms-of-use');

// Diagnostic route to check Relation response format
Route::get('/debug/relation-test', function () {
    // Simulate what RelationController returns
    $users = \App\Models\User::limit(10)->get();
    
    $format1 = $users->mapWithKeys(function ($user) {
        return [$user->id => $user->name];
    });
    
    $format2 = $users->map(function ($user) {
        return ['value' => $user->id, 'label' => $user->name];
    })->values();
    
    return response()->json([
        'orchid_version' => \Illuminate\Support\Composer::getPackageVersion('orchid/platform'),
        'expected_format' => $format1,
        'current_prod_format' => $format2,
        'note' => 'If production returns format2, there might be a middleware transforming responses'
    ]);
})->middleware('auth');
