<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoRecursoController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\TipoIncidenciaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\AdjuntoController;
use App\Http\Controllers\ElementoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\AuditaController;
use App\Http\Controllers\BloqueoController;

// RUTAS PÚBLICAS
// Ruta de estado de la API
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running'
    ]);
});

Route::controller(UsuarioController::class)->prefix('usuario')->group(function () {
    Route::post('/registro', 'register');
    Route::post('/login', 'login');
});

// RUTAS PROTEGIDAS (solo accesibles después de iniciar sesión)
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de Rol
    Route::controller(RolController::class)->prefix('roles')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Tipo de Recurso
    Route::controller(TipoRecursoController::class)->prefix('tipo-recurso')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Sesion
    Route::controller(SesionController::class)->prefix('sesion')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Calendario
    Route::controller(CalendarioController::class)->prefix('calendario')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Tipo de Incidencia
    Route::controller(TipoIncidenciaController::class)->prefix('tipo-incidencia')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Adjunto
    Route::controller(AdjuntoController::class)->prefix('adjunto')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de creación de adjuntos vinculados
    Route::controller(AdjuntoController::class)->group(function () {
        Route::post('/incidencia/{idIncidencia}/adjuntos', 'storeAdjuntoIncidencia');
        Route::post('/elemento/{idElemento}/adjuntos', 'storeAdjuntoElemento');
    });

    // Rutas de Usuario
    Route::controller(UsuarioController::class)->prefix('usuario')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Recurso
    Route::controller(RecursoController::class)->prefix('recurso')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Elemento
    Route::controller(ElementoController::class)->prefix('elemento')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Reserva
    Route::controller(ReservaController::class)->prefix('reserva')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Historial
    Route::controller(HistorialController::class)->prefix('historial')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Notificacion
    Route::controller(NotificacionController::class)->prefix('notificacion')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Incidencia
    Route::controller(IncidenciaController::class)->prefix('incidencia')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Audita
    Route::controller(AuditaController::class)->prefix('audita')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Rutas de Bloqueo
    Route::controller(BloqueoController::class)->prefix('bloqueo')->group(function () {
        Route::get('/', 'index');   
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });
});
