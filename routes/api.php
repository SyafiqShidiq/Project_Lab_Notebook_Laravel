<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RevisionController;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/notes/{note}/revision', [RevisionController::class, 'store']);
    Route::get('/notes/{note}/revisions', [RevisionController::class, 'index']);
});

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// FORGOT PASSWORD
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// NOTES
Route::get('/notes/trash', [NoteController::class, 'trash']);
Route::get('/notes/trash/{id}', [NoteController::class, 'trashed']);
Route::delete('/notes/{id}/force', [NoteController::class, 'forceDelete']);
Route::post('/notes/{id}/restore', [NoteController::class, 'restore']);

Route::apiResource('notes', NoteController::class);
