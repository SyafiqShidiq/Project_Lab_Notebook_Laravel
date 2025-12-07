<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\Api\NoteController;


Route::get('/notes/trash', [NoteController::class, 'trash']);
Route::get('/notes/trash/{id}', [NoteController::class, 'trashed']);
Route::delete('/notes/{id}/force', [NoteController::class, 'forceDelete']);
Route::post('/notes/{id}/restore', [NoteController::class, 'restore']);

Route::apiResource('notes', NoteController::class);
