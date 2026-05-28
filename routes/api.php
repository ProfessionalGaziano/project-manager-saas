<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ProjectRequestController;

// Rotta per ottenere l'utente autenticato
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotte protette da Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);

    // Tasks
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);

    // Project Requests
    Route::get('/project-requests', [ProjectRequestController::class, 'index']);
    Route::post('/project-requests', [ProjectRequestController::class, 'store']);

    // Login API — genera token
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout effettuato.']);
    });
});

// Login API — pubblico
Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!\Illuminate\Support\Facades\Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Credenziali non valide.'], 401);
    }

    $user = \App\Models\User::where('email', $request->email)->first();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Login effettuato con successo.',
        'token'   => $token,
        'user'    => $user,
    ]);
});