<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiaryEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/profile/photo/update', [UserController::class, 'updateProfilePhoto'])->name('profile.photo.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/bio', [UserController::class, 'showBio'])->name('profile.show-bio');
    Route::patch('/profile/bio', [UserController::class, 'updateBio'])->name('profile.update-bio');
    Route::resource('diary', DiaryEntryController::class);
    Route::get('/display_diary', [DiaryEntryController::class, 'display_diary'])->name('diary.display_diary');
    Route::get('/mockup', [DiaryEntryController::class, 'mockup'])->name('mockup');

});


require __DIR__.'/auth.php';
