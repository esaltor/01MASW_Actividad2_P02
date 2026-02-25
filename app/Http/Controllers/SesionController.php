<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class SesionController extends Controller
{
    /**
     * Validate session data sent by the client
     */
    private function validateSesion(Request $request): void
    {
        $rules = [
            'horaInicio' => ['required', 'date_format:H:i:s'],
            'horaFin'    => ['required', 'date_format:H:i:s'],
        ];

        $messages = [
            'horaInicio.required' => 'La hora de inicio es obligatoria.',
            'horaInicio.date_format' => 'La hora de inicio debe tener el formato H:i:s.',
            'horaFin.required' => 'La hora de fin es obligatoria.',
            'horaFin.date_format' => 'La hora de fin debe tener el formato H:i:s.',
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
            // Obtención del número de la página y del número de elementos por página
            $pageKey = (int) request()->query('pageKey', 1);
            $pageSize = (int) request()->query('pageSize', 10);

            // Límites de la paginación para evitar abusos
            $pageKey = max(1, $pageKey);
            $pageSize = min(max(1, $pageSize), 100);

            // Obtención del listado de sesiones paginado
            $sesiones = Sesion::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($sesiones),
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
        $this->validateSesion($request);

        try {
            $newSesion = new Sesion([
                'horaInicio' => $request->get('horaInicio'),
                'horaFin' => $request->get('horaFin'),
            ]);

            $newSesion->save();

            return response()->json(
                ResultResponse::ok($newSesion),
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
            $sesion = Sesion::findOrFail($id);

            return response()->json(
                ResultResponse::ok($sesion),
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

     public function edit(Request $request, $id)
    {
        try {
            $sesion = Sesion::findOrFail($id);

            $sesion->horaInicio = $request->get('horaInicio', $sesion->horaInicio);
            $sesion->horaFin = $request->get('horaFin', $sesion->horaFin);

            $sesion->save();

            return response()->json(
                ResultResponse::ok($sesion),
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
        $this->validateSesion($request);

        try {
            $sesion = Sesion::findOrFail($id);

            $sesion->horaInicio = $request->get('horaInicio');
            $sesion->horaFin = $request->get('horaFin');

            $sesion->save();

            return response()->json(
                ResultResponse::ok($sesion),
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
            $sesion = Sesion::findOrFail($id);
            $sesion->delete();

            return response()->json(
                ResultResponse::ok($sesion),
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