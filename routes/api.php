<?php
// routes/api.php - VERSIÓN LIMPIA

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Solo rutas API reales, sin duplicar las de web
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Eliminar todas las rutas duplicadas de tickets y messages
// Esas deben estar SOLO en routes/web.php
