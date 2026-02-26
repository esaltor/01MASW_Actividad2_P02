<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Http\Responses\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class ReservaController
{
    /**
     * Display a listing of the reservation.
     */
    public function index()
    {
        try {
            $query = Reserva::with(['recurso', 'sesion'])->orderBy('idReserva');

            // Filtro recurso
            if ($recurso = request()->query('idRecurso')) {
                $query->where('idRecurso', $recurso);
            }

            // Obtención del número de la página y del número de elementos por página
            $pageKey = (int) request()->query('pageKey', 1);
            $pageSize = (int) request()->query('pageSize', 10);

            // Límites de la paginación para evitar abusos
            $pageKey = max(1, $pageKey);
            $pageSize = min(max(1, $pageSize), 100);

            // Obtención del listado de roles paginado
            $reservas = $query->paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($reservas),
                ResultResponse::SUCCESS_CODE
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );

        } catch (\Throwable $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    $e->getMessage() // para ver el error real
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {

        // Validación de los campos introducidos
        $this->validateReserva($request);

        try {
            $newReserva = new Reserva([
                'estado' => $request->input('estado'),
                'fecha' => $request->input('fecha'),
                'idUsuario' => $request->user()->idUsuario, // Se asigna el usuario autenticado como creador de la incidencia
                'idRecurso' => $request->input('idRecurso'),
                'idSesion' => $request->input('idSesion'),
            ]);

            $newReserva->save();

            return response()->json(
                ResultResponse::ok($newReserva),
                ResultResponse::SUCCESS_CODE
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );

        } catch (\Throwable $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    $e->getMessage() // para ver el error real
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);

            return response()->json(
                ResultResponse::ok($reserva),
                ResultResponse::SUCCESS_CODE
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );

        } catch (\Throwable $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    $e->getMessage() // para ver el error real
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Show the form for editing the specified reservation.
     */
    public function edit(Request $request, $id)
    {
        try {
            $reserva = Reserva::with(['usuario', 'recurso', 'sesion'])
            ->findOrFail($id);

            $reserva->estado = $request->get('estado', $reserva->estado);
            $reserva->fecha = $request->get('fecha', $reserva->fecha);
            $reserva->idRecurso = $request->get('idRecurso', $reserva->idRecurso);
            $reserva->idSesion = $request->get('idSesion', $reserva->idSesion);

            $reserva->save();

            return response()->json(
                ResultResponse::ok($reserva),
                ResultResponse::SUCCESS_CODE
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );

        } catch (\Throwable $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    $e->getMessage() // para ver el error real
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los campos introducidos
        $this->validateReserva($request,(int) $id);

        try {
            $reserva = Reserva::with(['usuario', 'recurso', 'sesion'])
                ->findOrFail($id);

            $reserva->estado = $request->get('estado', $reserva->estado);
            $reserva->fecha = $request->get('fecha', $reserva->fecha);
            $reserva->idRecurso = $request->get('idRecurso', $reserva->idRecurso);
            $reserva->idSesion = $request->get('idSesion', $reserva->idSesion);

            $reserva->save();

            return response()->json(
                ResultResponse::ok($reserva),
                ResultResponse::SUCCESS_CODE
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );

        } catch (\Throwable $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    $e->getMessage() // para ver el error real
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Remove the specified reservation from storage.
     */
    public function destroy($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
            $reserva->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($reserva),
                ResultResponse::SUCCESS_CODE
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                ),
                ResultResponse::NOT_FOUND_CODE
            );

        } catch (\Throwable $e) {

            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    $e->getMessage() // para ver el error real
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    private function validateReserva(Request $request, ?int $id = null): void
    {
        $rules = [
            'estado' => ['required', 'string', 'max:50'],
            'fecha' => ['required', 'date', 'exists:CALENDARIO,fecha'],
            'idRecurso' => ['required', 'integer', 'exists:RECURSO,idRecurso'],
            'idSesion' => ['nullable', 'integer', 'exists:SESION,idSesion'],
        ];

        $messages = [
            'estado.required' => 'El estado es obligatorio.',
            'estado.string' => 'El estado debe ser texto.',
            'estado.max' => 'El estado no puede superar 50 caracteres.',

            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe tener un formato válido.',

            'idRecurso.required' => 'El recurso es obligatorio.',
            'idRecurso.integer' => 'El recurso debe ser un número.',
            'idRecurso.exists' => 'El recurso indicado no existe.',

            'idSesion.integer' => 'La sesión debe ser un número.',
            'idSesion.exists' => 'La sesión indicada no existe.',
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

}
