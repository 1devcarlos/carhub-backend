<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:api'])->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'getUser']);

  //? Usuário
  Route::get('/profile', [UserController::class, 'show']);
  Route::put('/profile/update', [UserController::class, 'update']);
  Route::post('/profile/image', [UserController::class, 'uploadPhoto']);

  Route::get('/company', [CompanyController::class, 'index']);
  Route::post('/company/create', [CompanyController::class, 'store']);
  Route::put('/company/update/{id}', [CompanyController::class, 'update']);
  Route::delete('/company/delete/{id}', [CompanyController::class, 'destroy']);
});
