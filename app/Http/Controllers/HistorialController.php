<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class HistorialController extends Controller
{
    /**
     * Validate data sent by the client
     */
    function validateHistorial(Request $request, ?int $id = null): void {
        $rules = [
            'fecha' => ['required', 'date'],
            'horaInicio' => ['required', 'date_format:H:i:s'],
            'horaFin' => [
                'required',
                'date_format:H:i:s',
                'after:horaInicio'
            ],
            'idUsuario' => [
                'required',
                'integer',
                Rule::exists('USUARIO', 'idUsuario')
            ],
            'idRecurso' => [
                'required',
                'integer',
                Rule::exists('RECURSO', 'idRecurso')
            ],
        ];
    
        $messages = [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no tiene un formato válido.',
    
            'horaInicio.required' => 'La hora de inicio es obligatoria.',
            'horaInicio.date_format' => 'La hora de inicio debe tener formato HH:MM:SS.',
    
            'horaFin.required' => 'La hora de fin es obligatoria.',
            'horaFin.date_format' => 'La hora de fin debe tener formato HH:MM:SS.',
            'horaFin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
    
            'idUsuario.required' => 'El usuario es obligatorio.',
            'idUsuario.integer' => 'El idUsuario debe ser numérico.',
            'idUsuario.exists' => 'El usuario indicado no existe.',
    
            'idRecurso.required' => 'El recurso es obligatorio.',
            'idRecurso.integer' => 'El idRecurso debe ser numérico.',
            'idRecurso.exists' => 'El recurso indicado no existe.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json(
                    ResultResponse::fail(
                        ResultResponse::ERROR_CODE,
                        ResultResponse::TXT_ERROR_CODE,
                        $validator->errors()->toArray()
                    ),
                    ResultResponse::ERROR_CODE
                )
            );
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Historial::query();

            // Filtro recurso
            if ($request->filled('idRecurso')) {
                $query->where('idRecurso', $request->idRecurso);
            }

            // Filtro fecha
            if ($request->filled('fecha')) {
                $query->whereDate('fecha', '>=', $request->fecha);
            }

            // Ordenacion antes de paginar
            $query->orderBy('fecha', 'desc');

            // Paginacion segura
            $pageKey = (int) $request->query('pageKey', 1);
            $pageSize = (int) $request->query('pageSize', 10);

            $pageKey = max(1, $pageKey);
            $pageSize = min(max(1, $pageSize), 100);

            $notificaciones = $query->with('recurso')->paginate(
                $pageSize,
                ['*'],
                'pageKey',
                $pageKey
            );

            return response()->json(
                ResultResponse::ok($notificaciones),
                ResultResponse::SUCCESS_CODE
            );
        } catch (Throwable $e) { 
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    ResultResponse::TXT_INTERNAL_SERVER_ERROR_CODE,
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los campos introducidos
        $this->validateHistorial($request);

        try {
            $newHistorial = new Historial([
                'fecha' => $request->get('fecha'),
                'horaInicio' => $request->get('horaInicio'),
                'horaFin' => $request->get('horaFin'),
                'idUsuario' => (int) $request->get('idUsuario'),
                'idRecurso' => (int) $request->get('idRecurso'),
            ]);

            $newHistorial->save();

            return response()->json(
                ResultResponse::ok($newHistorial),
                ResultResponse::SUCCESS_CODE
            );
        } catch (Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    ResultResponse::TXT_INTERNAL_SERVER_ERROR_CODE,
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $historial = Historial::findOrFail($id);

            return response()->json(
                ResultResponse::ok($historial),
                ResultResponse::SUCCESS_CODE
            );
        } catch (Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        try {
            $historial = Historial::findOrFail($id);

            $historial->fecha = $request->get('fecha', $historial->fecha);
            $historial->horaInicio = $request->get('horaInicio', $historial->horaInicio);
            $historial->horaFin = $request->get('horaFin', $historial->horaFin);
            $historial->idUsuario = (int) $request->get('idUsuario', $historial->idUsuario);
            $historial->idRecurso = (int) $request->get('idRecurso', $historial->idRecurso);

            $historial->save();

            return response()->json(
                ResultResponse::ok($historial),
                ResultResponse::SUCCESS_CODE
            );
        } catch(Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los campos introducidos
        $this->validateHistorial($request, (int) $id);

        try {
            $historial = Historial::findOrFail($id);

            $historial->fecha = $request->get('fecha');
            $historial->horaInicio = $request->get('horaInicio');
            $historial->horaFin = $request->get('horaFin');
            $historial->idUsuario = (int) $request->get('idUsuario');
            $historial->idRecurso = (int) $request->get('idRecurso');

            $historial->save();

            return response()->json(
                ResultResponse::ok($historial),
                ResultResponse::SUCCESS_CODE
            );
        } catch(Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $historial = Historial::findOrFail($id);
            $historial->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($historial),
                ResultResponse::SUCCESS_CODE
            );
        } catch(Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );
        }
    }
}
