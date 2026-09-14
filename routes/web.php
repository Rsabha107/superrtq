<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FanController;
use App\Http\Controllers\Admin\GameSessionController;
use App\Http\Controllers\Admin\LostFoundController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\PointsRuleController;
use App\Http\Controllers\Admin\PredictionController;
use App\Http\Controllers\Admin\RedemptionController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\StadiumController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('fans', [FanController::class, 'index'])->name('fans.index');
        Route::get('fans/{fan}', [FanController::class, 'show'])->name('fans.show');

        Route::resource('events', EventController::class)->except('show');
        Route::resource('stadiums', StadiumController::class)->except('show');
        Route::resource('matches', MatchController::class)->except('show');
        Route::resource('points-rules', PointsRuleController::class)->except('show');
        Route::resource('rewards', RewardController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');

        Route::get('redemptions', [RedemptionController::class, 'index'])->name('redemptions.index');
        Route::get('predictions', [PredictionController::class, 'index'])->name('predictions.index');
        Route::get('game-sessions', [GameSessionController::class, 'index'])->name('game-sessions.index');

        Route::get('lost-found', [LostFoundController::class, 'index'])->name('lost-found.index');
        Route::put('lost-found/{lostFoundReport}', [LostFoundController::class, 'update'])->name('lost-found.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
