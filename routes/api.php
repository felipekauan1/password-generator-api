<?php

use App\Http\Controllers\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/passwords', [PasswordController::class, 'generate']);
Route::get('/passwords', [PasswordController::class, 'index']);
Route::delete('/passwords/{password}', [PasswordController::class, 'destroy']);
