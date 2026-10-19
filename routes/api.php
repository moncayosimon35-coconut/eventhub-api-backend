<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MetricasController;
use Illuminate\Support\Facades\Route;

// Rutas Públicas (Cualquiera puede acceder)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/eventos', [EventoController::class, 'index']);

// Rutas Protegidas (Requieren Token Bearer válido en los Headers)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::put('/eventos/{id}', [EventoController::class, 'update']);
    Route::get('/admin/metricas', [MetricasController::class, 'index']);
});
