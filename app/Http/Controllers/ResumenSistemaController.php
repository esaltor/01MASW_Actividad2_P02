<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Incidencia;
use App\Models\Bloqueo;
use App\Models\Recurso;
use App\Models\Notificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResumenSistemaController
{
    public function resumen(Request $request)
    {
        $reservasActivas = Reserva::where('estado', 'Pendiente')->count();

        $incidenciasAbiertas = Incidencia::where('estado', '!=', 'Cerrada')->count();

        $incidenciasCerradas = Incidencia::where('estado', '=', 'Cerrada')->count();

        // Dia de la semana actual: 1 = lunes, 7 = domingo
        $diaSemanaHoy = Carbon::now()->dayOfWeekIso;

        // Contar recursos bloqueados hoy (sin duplicados)
        $recursosBloqueados = Bloqueo::where('diaSemana', $diaSemanaHoy)
            ->distinct('idRecurso')
            ->count('idRecurso');

        $recursosDisponibles = Recurso::count() - $recursosBloqueados;

        $user = $request->user(); // usuario autenticado via token
        $mensajes = Notificacion::where('idUsuario', $user->idUsuario)->count();

        return response()->json([
            'reservasActivas' => $reservasActivas,
            'incidenciasAbiertas' => $incidenciasAbiertas,
            'incidenciasCerradas' => $incidenciasCerradas,
            'recursosBloqueados' => $recursosBloqueados,
            'recursosDisponibles' => $recursosDisponibles,
            'mensajes' => $mensajes,
        ]);
    }
}