<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Models\StreamController;
use App\Models\User;
use App\Notifications\Target;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('auth.login');
})->middleware(['auth', 'verified'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/l/{link}', [LandingController::class, 'show'])->name('landing.show');
Route::post('/send', [LandingController::class, 'send'])->name('landing.send');
Route::get('/dubl', [LandingController::class, 'duplicate'])->name('landing.duplicate');
Route::get('/thanks', [LandingController::class, 'thanks'])->name('landing.thanks');

Route::post('/streams', [\App\Http\Controllers\StreamController::class, 'store'])->name('streams.store');

//Route::get('/send-notification', function () {
//    $user = User::find(19); // The user you want to notify
//    $user->notify(new Target($user));
//
//    return "Notification sent!";
//});
//
//Route::get('/notifications', [\App\Http\Controllers\Target::class, 'index'])->name('user.notifications');


require __DIR__.'/auth.php';
