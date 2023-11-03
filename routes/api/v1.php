<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UploadController;
use App\Http\Controllers\Api\V1\UserController;

Route::get('/', UserController::class)->name('/');

Route::apiResource('uploads', UploadController::class)->middleware('auth:sanctum');