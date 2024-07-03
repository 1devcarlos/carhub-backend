<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarManagementController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'getUser']);

  // Usuário
  Route::get('/profile', [UserController::class, 'show']);
  Route::put('/profile/update', [UserController::class, 'update']);
  Route::post('/profile/image', [UserController::class, 'uploadPhoto']);

  // Proteção para funcionários/dono da locadora
  Route::middleware(['role:employee'])->group(function () {
    // Empresa
    Route::prefix('/company')->group(function () {
      Route::get('/', [CompanyController::class, 'index']);
      Route::post('/create', [CompanyController::class, 'store']);
      Route::put('/update/{id}', [CompanyController::class, 'update']);
      Route::delete('/delete/{id}', [CompanyController::class, 'destroy']);
    });

    // Carros
    Route::prefix('/cars')->group(function () {
      Route::get('/', [CarController::class, 'index']);
      Route::post('/create', [CarController::class, 'store']);
      Route::post('/uploadPhoto/{id}', [CarController::class, 'uploadPhoto']);
      Route::put('/update/{id}', [CarController::class, 'update']);
      Route::delete('/delete/{id}', [CarController::class, 'destroy']);
    });

    // Gestão de Carros
    Route::prefix('/cars-management')->group(function () {
      Route::get('/', [CarManagementController::class, 'index']);
      Route::put('/update-status/{id}', [CarManagementController::class, 'updateStatus']);
    });
  });

  //Qual usuário autenticado pode acessar

  // Reserva de Carros
  Route::post('/reservations', [ReservationController::class, 'store']);
  Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

  // Locação de Carros
  Route::prefix('/rentals')->group(function () {
    Route::post('/', [RentalController::class, 'store']);
    Route::delete('/{id}', [RentalController::class, 'destroy']);
  });
});
