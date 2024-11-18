<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\RealTimeResultController;



Route::get('/', function () {
    return view('welcome');

});


// Route untuk halaman voting
Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

// Route untuk halaman peserta (admin)
Route::get('/dashboard/participants', [ParticipantController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.participants');
Route::get('/dashboard/participants/create', [ParticipantController::class, 'create'])->middleware(['auth', 'verified'])->name('dashboard.participants.create');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard/participants/edit/{participant}', [ParticipantController::class, 'edit'])->name('dashboard.participants.edit');
    Route::post('dashboard/participants/edit/{participant}', [ParticipantController::class, 'update'])->name('dashboard.participants.update');
    Route::delete('dashboard/participants/{participant}', [ParticipantController::class, 'destroy'])->name('dashboard.participants.destroy');
});

Route::post('/dashboard/participants', [ParticipantController::class, 'store'])->name('dashboard.participants.store');

// Route untuk Admin
Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/dashboard/settings', [AdminController::class, 'settings'])->middleware(['auth', 'verified'])->name('dashboard.settings');
Route::put('/dashboard/voting-limit', [AdminController::class, 'updateVotingLimit'])->name('dashboard.updateVotingLimit');
Route::put('/dashboard/profile', [AdminController::class, 'updateProfile'])->name('dashboard.updateProfile');

// Route untuk Kandidat
Route::get('/dashboard/candidates', [CandidateController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.candidates');
Route::get('/dashboard/candidates/create', [CandidateController::class, 'create'])->middleware(['auth', 'verified'])->name('dashboard.candidates.create');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard/candidates/edit/{candidate}', [CandidateController::class, 'edit'])->name('dashboard.candidates.edit');
    Route::delete('dashboard/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('dashboard.candidates.destroy');
});

Route::post('/dashboard/candidates', [CandidateController::class, 'store'])->name('dashboard.candidates.store');

// Route::resource('/dashboard/candidates', CandidateController::class);


Route::get('/dashboard', function () {
    return view('admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
