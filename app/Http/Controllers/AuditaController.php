<?php

namespace App\Http\Controllers;

use App\Models\Audita;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class AuditaController extends Controller
{
    /**
     * Validate data sent by the client
     */
    public function validateAudita(Request $request, ?int $id = null): void
    {
        $rules = [
            'fechaHora' => ['required', 'date'],
            'accion' => ['required', 'string', 'max:10'],
            'idReserva' => ['required', 'integer', Rule::exists('RESERVA', 'idReserva')],
            'idUsuario' => ['required', 'integer', Rule::exists('USUARIO', 'idUsuario')],
        ];

        $messages = [
            'fechaHora.required' => 'La fechaHora es obligatoria.',
            'fechaHora.date' => 'La fechaHora no tiene un formato válido.',

            'accion.required' => 'La acción es obligatoria.',
            'accion.string' => 'La acción debe ser texto.',
            'accion.max' => 'La acción no puede superar 10 caracteres.',

            'idReserva.required' => 'La reserva es obligatoria.',
            'idReserva.integer' => 'El idReserva debe ser numérico.',
            'idReserva.exists' => 'La reserva indicada no existe.',

            'idUsuario.required' => 'El usuario es obligatorio.',
            'idUsuario.integer' => 'El idUsuario debe ser numérico.',
            'idUsuario.exists' => 'El usuario indicado no existe.',
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
    public function index()
    {
        try {
            $pageKey = (int) request()->query('pageKey', 1);
            $pageSize = (int) request()->query('pageSize', 10);

            $pageKey = max(1, $pageKey);
            $pageSize = min(max(1, $pageSize), 100);

            $auditorias = Audita::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($auditorias),
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
        $this->validateAudita($request);

        try {
            $newAudita = new Audita([
                'fechaHora' => $request->get('fechaHora'),
                'accion' => $request->get('accion'),
                'idReserva' => (int) $request->get('idReserva'),
                'idUsuario' => (int) $request->get('idUsuario'),
            ]);

            $newAudita->save();

            return response()->json(
                ResultResponse::ok($newAudita),
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
            $audita = Audita::findOrFail($id);

            return response()->json(
                ResultResponse::ok($audita),
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
            $audita = Audita::findOrFail($id);

            $audita->fechaHora = $request->get('fechaHora', $audita->fechaHora);
            $audita->accion = $request->get('accion', $audita->accion);
            $audita->idReserva = (int) $request->get('idReserva', $audita->idReserva);
            $audita->idUsuario = (int) $request->get('idUsuario', $audita->idUsuario);

            $audita->save();

            return response()->json(
                ResultResponse::ok($audita),
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los campos introducidos
        $this->validateAudita($request, (int) $id);

        try {
            $audita = Audita::findOrFail($id);

            $audita->fechaHora = $request->get('fechaHora');
            $audita->accion = $request->get('accion');
            $audita->idReserva = (int) $request->get('idReserva');
            $audita->idUsuario = (int) $request->get('idUsuario');

            $audita->save();

            return response()->json(
                ResultResponse::ok($audita),
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $audita = Audita::findOrFail($id);
            $audita->delete(); // Soft delete

            return response()->json(
                ResultResponse::ok($audita),
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
}
