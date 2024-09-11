<?php

use App\Models\StreamController;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/{panel}', function ($panel) {
    // Logic to load the appropriate panel
    return Filament::renderPanel($panel);
})->name('filament.dashboard');


Route::get('/l/{link}', [StreamController::class, 'show'])->name('stream.show');

Route::group(['middleware' => ['auth', 'verified']], function () {
    // Protected routes that require email verification
});

Route::view('/', 'welcome');

Route::get('/statistics', [\App\Http\Controllers\Statistics::class, 'index'])->name('statistics');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
