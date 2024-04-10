<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/users', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'destroy']);
});
// Route::delete('/users/{user}', [UserController::class, 'index']);
