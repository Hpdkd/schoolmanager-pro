<?php

use App\Http\Controllers\API\StudentApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        return response()->json(['message' => 'Identifiants invalides.'], 401);
    }

    $user  = \App\Models\User::where('email', $request->email)->firstOrFail();
    $token = $user->createToken('mobile-app')->plainTextToken;

    return response()->json(['token' => $token, 'user' => ['id' => $user->id, 'name' => $user->name, 'role' => $user->role]]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', function (\Illuminate\Http\Request $r) {
        $r->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté.']);
    });

    Route::get('/me', fn(\Illuminate\Http\Request $r) => response()->json($r->user()));

    Route::get('/students',           [StudentApiController::class, 'index']);
    Route::post('/students',          [StudentApiController::class, 'store']);
    Route::get('/students/{student}', [StudentApiController::class, 'show']);
    Route::get('/students/{student}/grades', [StudentApiController::class, 'grades']);
    Route::post('/grades',            [StudentApiController::class, 'storeGrade']);
});
