<?php

use App\Http\Controllers\{ProfileController, DashboardController, LandingController, AuthController, TicketViewController, TicketController, MessageController, TechnicianStatusController};
use Illuminate\Support\Facades\Route;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web de la aplicación. Estas rutas se cargan
| mediante el RouteServiceProvider y se agrupan en el middleware "web".
|
*/

// Rutas públicas
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/servicio/{id}', [LandingController::class, 'servicios'])->name('servicio');
Route::get('/categoria/{id}', [LandingController::class, 'mostrarPorCategoria'])->name('productos_por_categoria');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas del panel (dashboard) protegidas bajo "auth" y "role:admin"
Route::middleware(['auth', 'role:admin|empresarial|tecnico'])->prefix('dashboard')->group(function () {
    // Ruta principal del dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD general para todas las secciones dinámicas
    Route::post('/store/{section}', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::put('/update/{section}/{id}', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::delete('/delete/{section}/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');

    // Funcionalidades específicas de exportación para inventario
    Route::get('/export-pdf/{section}/{id}', [DashboardController::class, 'exportPdf'])->name('dashboard.exportPdf');
    Route::get('/export-excel/{section}', [DashboardController::class, 'exportExcel'])->name('dashboard.exportExcel');
});

Route::resource('tickets', TicketController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/technician/status', [TicketController::class, 'updateStatus'])->name('technician.status.update');
    Route::post('/technician/status', [TicketController::class, 'updateTechnicianStatus'])->name('technician.status.update');
    Route::post('/tickets/{id}/self-assign', [TicketController::class, 'selfAssign'])->name('tickets.selfAssign');
});


require __DIR__ . '/auth.php';
