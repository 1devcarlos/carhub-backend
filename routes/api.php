<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:api'])->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'getUser']);

  Route::middleware(['isAdmin'])->group(function () {
    Route::get('/admin', function () {
      //fallback de admin.
      return "Usuário Admin";
    });
  });

  Route::middleware(['isEmployee'])->group(function () {
    Route::get('/employee', function () {
      //fallback de funcionário.
      return "Funcionário";
    });
  });

  Route::middleware(['isClient'])->group(function () {
    Route::get('/client', function () {
      //fallback de cliente.
      return "Client";
    });
  });
});
