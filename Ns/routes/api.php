<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TechnicianStatusController;
use App\Http\Controllers\TicketMessageController;
use App\Http\Controllers\MessageController;

// Rutas RESTful para tickets con nombres únicos para la API
Route::apiResource('tickets', TicketController::class)->names([
    'index'   => 'api.tickets.index',
    'store'   => 'api.tickets.store',
    'show'    => 'api.tickets.show',
    'update'  => 'api.tickets.update',
    'destroy' => 'api.tickets.destroy',
]);

// Rutas protegidas por Sanctum para acciones adicionales
Route::middleware(['auth:sanctum'])->group(function () {
    // Acciones adicionales sobre tickets (si necesitas endpoints personalizados)
    Route::post('/tickets/{id}/read', [TicketMessageController::class, 'markAsRead']);
    // Estado del técnico
    Route::get('/technician/status', [TechnicianStatusController::class, 'getStatus']);
    Route::post('/technician/status', [TechnicianStatusController::class, 'updateStatus']);
    // Mensajes
    Route::post('/messages', [MessageController::class, 'store'])->name('api.messages.store');
});

// Endpoint para CSRF cookie (si usas Sanctum SPA)
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Obtener usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/typing', function (Illuminate\Http\Request $request) {
    // Puedes emitir un evento aquí si lo deseas, pero para probar solo responde:
    return response()->json(['status' => 'ok']);
});
