<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarManagementController;
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

  //? Empresa
  Route::get('/company', [CompanyController::class, 'index']);
  Route::post('/company/create', [CompanyController::class, 'store']);
  Route::put('/company/update/{id}', [CompanyController::class, 'update']);
  Route::delete('/company/delete/{id}', [CompanyController::class, 'destroy']);

  //? Carros
  Route::get('/cars', [CarController::class, 'index']);
  Route::post('/cars/create', [CarController::class, 'store']);
  Route::put('/cars/update/{id}', [CarController::class, 'update']);
  Route::post('/cars/uploadPhoto/{id}', [CarController::class, 'uploadPhoto']);
  Route::delete('/cars/delete/{id}', [CarController::class, 'destroy']);

  //? Gestão Carros
  Route::get('/cars-management', [CarManagementController::class, 'index']);
  Route::put('/cars-management/update-status/{id}', [CarManagementController::class, 'updateStatus']);
});
