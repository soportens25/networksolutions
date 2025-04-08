<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TechnicianStatusController;
use App\Http\Controllers\TicketMessageController;
use App\Http\Controllers\MessageController;


Route::apiResource('tickets', TicketController::class);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::put('/tickets/{id}', [TicketController::class, 'update']);
    //
    Route::get('/technician/status', [TechnicianStatusController::class, 'getStatus']);
    Route::post('/technician/status', [TechnicianStatusController::class, 'updateStatus']);
    //
    Route::post('/tickets/{id}/read', [TicketMessageController::class, 'markAsRead']);
    //
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
