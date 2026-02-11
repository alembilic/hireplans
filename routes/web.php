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
    
    // Get Orchid version from composer.lock
    $composerLock = json_decode(file_get_contents(base_path('composer.lock')), true);
    $orchidVersion = collect($composerLock['packages'] ?? [])
        ->firstWhere('name', 'orchid/platform')['version'] ?? 'unknown';
    
    return response()->json([
        'orchid_version' => $orchidVersion,
        'expected_format' => $format1,
        'current_prod_format' => $format2,
        'note' => 'If production returns format2, there might be a middleware transforming responses'
    ]);
})->middleware('auth');

// Test actual Orchid RelationController endpoint
Route::post('/debug/test-orchid-relation', function (\Illuminate\Http\Request $request) {
    // Simulate the actual Orchid Relation request
    $users = \App\Models\User::where('name', 'like', '%' . $request->get('search', '') . '%')
        ->limit(10)
        ->get();
    
    // This is what Orchid's RelationController does (lines 87-103)
    $result = $users->mapWithKeys(function ($user) {
        return [$user->id => $user->name];
    });
    
    return response()->json([
        'test_type' => 'Simulated Orchid RelationController',
        'format' => 'key-value object',
        'data' => $result,
        'note' => 'This should match what /platform/relation returns'
    ]);
})->middleware('auth');
