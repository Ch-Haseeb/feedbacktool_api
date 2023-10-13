<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentFeedbackController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\StatisticsController;


Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/feedback', [FeedbackController::class, 'store']);

    Route::get('/feedback', [FeedbackController::class, 'index']);

    Route::put('/feedback/{id}',[FeedbackController::class, 'update']);

    Route::delete('/feedback/{id}',[FeedbackController::class, 'destroy']);

    Route::post('/feedback/{id}/vote',  [FeedbackController::class, 'vote']);

    Route::get('/feedback/comment', [CommentFeedbackController::class, 'index']);

    Route::post('/feedback/{id}/comment', [CommentFeedbackController::class, 'store']);

    Route::put('/feedback/{feedbackId}/comment/{commentId}', [CommentFeedbackController::class, 'update']);

    Route::delete('/feedback/{feedbackId}/comment/{commentId}', [CommentFeedbackController::class, 'destroy']);    

    Route::get('/profile', [AuthController::class, 'show']);

    Route::put('/profile/update', [AuthController::class, 'update']);

    Route::get('/user_feedback', [AuthController::class, 'user_feedback']);

  
});
Route::get('/statistics', [StatisticsController::class, 'countStatistics']);
