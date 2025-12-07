<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\AuthController;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
