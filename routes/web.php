<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\RealTimeResultController;
use App\Http\Controllers\VoterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');

});

// Voting Routes
Route::get('/vote', [VoteController::class, 'index'])->name('vote');
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

// Admin Routes
Route::middleware(['auth:admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/admin/voting-limit', [AdminController::class, 'updateVotingLimit'])->name('admin.updateVotingLimit');
    Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Participants Management
    Route::get('/admin/participants', [ParticipantController::class, 'index'])->name('admin.participants');
    Route::get('/admin/participants/create', [ParticipantController::class, 'create'])->name('admin.participants.create');
    Route::post('/admin/participants', [ParticipantController::class, 'store'])->name('admin.participants.store');
    Route::get('/admin/participants/edit/{participant}', [ParticipantController::class, 'edit'])->name('admin.participants.edit');
    Route::post('/admin/participants/edit/{participant}', [ParticipantController::class, 'update'])->name('admin.participants.update');
    Route::delete('/admin/participants/{participant}', [ParticipantController::class, 'destroy'])->name('admin.participants.destroy');
    Route::post('/admin/participants/import', [ParticipantController::class, 'import'])->name('admin.participants.import');
    Route::delete('/admin/participants', [ParticipantController::class, 'destroyAll'])->name('admin.participants.destroyAll');
    Route::get('/admin/participants/{participant}/check-voted', [ParticipantController::class, 'checkVoted'])->name('participants.check-voted');

    // Candidates Management
    Route::get('/admin/candidates', [CandidateController::class, 'index'])->name('admin.candidates');
    Route::get('/admin/candidates/create', [CandidateController::class, 'create'])->name('admin.candidates.create');
    Route::post('/admin/candidates', [CandidateController::class, 'store'])->name('admin.candidates.store');
    Route::get('/admin/candidates/edit/{candidate}', [CandidateController::class, 'edit'])->name('admin.candidates.edit');
    Route::put('/admin/candidates/{candidate}', [CandidateController::class, 'update'])->name('admin.candidates.update');
    Route::delete('/admin/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('admin.candidates.destroy');
});

require __DIR__.'/auth.php';
