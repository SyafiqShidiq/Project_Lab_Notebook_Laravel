<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    $user = User::findOrFail($id);

    // validasi hash email
    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        $hash
    )) {
        return response()->json([
            'message' => 'Link verifikasi tidak valid'
        ], 403);
    }
    // kalau sudah diverifikasi
    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'message' => 'Email sudah diverifikasi'
        ]);
    }

    // verifikasi
    $user->markEmailAsVerified();
    event(new Verified($user));

    return response()->json([
        'message' => 'Email berhasil diverifikasi'
    ]);
})->name('verification.verify');

Route::post('/forgotpassword', function (Request $request){
    $request->validate([
        'email' => 'required|email'
    ]);
    $status = Password::sendResetLink(
        $request->only('email')
    );
    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Reset link sent'])
        : response()->json(['message' => __($status)], 400);
});
Route::post('/resetpassword', function (Request $request){
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6'
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );
    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password reset sukses'])
        : response()->json(['message' => __($status)], 400);
});

Route::middleware('auth:sanctum', 'verified')->group(function () {
    
    Route::get('/notes/trash', [NoteController::class, 'trash']);
    Route::get('/notes/trash/{id}', [NoteController::class, 'trashed']);
    Route::delete('/notes/{id}/force', [NoteController::class, 'forceDelete']);
    Route::post('/notes/{id}/restore', [NoteController::class, 'restore']);
    
    Route::apiResource('notes', NoteController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
