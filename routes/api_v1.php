<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\FanController;
use App\Http\Controllers\Api\V1\GameSessionController;
use App\Http\Controllers\Api\V1\JourneyController;
use App\Http\Controllers\Api\V1\LostFoundController;
use App\Http\Controllers\Api\V1\MatchController;
use App\Http\Controllers\Api\V1\PredictionController;
use App\Http\Controllers\Api\V1\RewardController;
use App\Http\Controllers\Api\V1\StadiumController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);

Route::get('/stadiums', [StadiumController::class, 'index']);
Route::get('/stadiums/{stadium}', [StadiumController::class, 'show']);

Route::get('/matches/groups', [MatchController::class, 'groups']);
Route::get('/matches', [MatchController::class, 'index']);
Route::get('/matches/{match}', [MatchController::class, 'show']);

Route::get('/rewards', [RewardController::class, 'index']);
Route::get('/rewards/{reward}', [RewardController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me/details', [AuthController::class, 'updateDetails']);
    Route::put('/me/profile', [AuthController::class, 'updateProfile']);

    Route::get('/fan', [FanController::class, 'show']);
    Route::get('/fan/points', [FanController::class, 'points']);
    Route::get('/fan/points/transactions', [FanController::class, 'pointsTransactions']);

    Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem']);

    Route::get('/me/predictions', [PredictionController::class, 'mine']);
    Route::post('/matches/{match}/predict', [PredictionController::class, 'store']);

    Route::post('/games/sessions', [GameSessionController::class, 'store']);

    Route::get('/me/journeys', [JourneyController::class, 'index']);
    Route::post('/me/journeys', [JourneyController::class, 'store']);

    Route::get('/me/lost-found', [LostFoundController::class, 'index']);
    Route::post('/me/lost-found', [LostFoundController::class, 'store']);
});
