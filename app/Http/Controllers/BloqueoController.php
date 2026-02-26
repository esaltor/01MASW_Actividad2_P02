<?php

namespace App\Http\Controllers;

use App\Models\Bloqueo;
use Illuminate\Http\Request;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BloqueoController
{
    /**
     * Display a listing of the lock.
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

            // Obtención del listado de bloqueos paginado
            $bloqueos = Bloqueo::with(['recurso', 'sesion'])
                ->orderBy('idRecurso')
                ->paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($bloqueos),
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
     * Store a newly created lock in storage.
     */
    public function store(Request $request)
    {
        // Validación de los campos introducidos
        $this->validateBloqueo($request);

        try {
            $newBloqueo = new Bloqueo([
                'idRecurso' => $request->get('idRecurso'),
                'diaSemana' => $request->get('diaSemana'),
                'idSesion' => $request->get('idSesion'),
                'motivoBloqueo' => $request->get('motivoBloqueo'),
            ]);

            $newBloqueo->save();

            return response()->json(
                ResultResponse::ok($newBloqueo),
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
     * Display the specified resource.
     */
    public function show($idRecurso, $diaSemana, $idSesion)
    {
        try {
            $bloqueo = Bloqueo::where('idRecurso', $idRecurso)
            ->where('diaSemana', $diaSemana)
            ->where('idSesion', $idSesion)
            ->firstOrFail();

            return response()->json(
                ResultResponse::ok($bloqueo),
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
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $idRecurso, $diaSemana, $idSesion)
    {
        try {
            $bloqueo = Bloqueo::where('idRecurso', $idRecurso)
                ->where('diaSemana', $diaSemana)
                ->where('idSesion', $idSesion)
                ->firstOrFail();

            $this->validateBloqueo($request, true);

            $bloqueo->motivoBloqueo = $request->get('motivoBloqueo', $bloqueo->motivoBloqueo);

            $bloqueo->save();

            return response()->json(
                ResultResponse::ok($bloqueo),
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idRecurso, $diaSemana, $idSesion)
    {
        try {
            $bloqueo = Bloqueo::where('idRecurso', $idRecurso)
                ->where('diaSemana', $diaSemana)
                ->where('idSesion', $idSesion)
                ->firstOrFail();

            $this->validateBloqueo($request);

            $bloqueo->motivoBloqueo = $request->get('motivoBloqueo', $bloqueo->motivoBloqueo);

            $bloqueo->save();

            return response()->json(
                ResultResponse::ok($bloqueo),
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
     * Remove the specified resource from storage.
     */
    public function destroy($idRecurso, $diaSemana, $idSesion)
    {
        try {
            $deleted = Bloqueo::where('idRecurso', $idRecurso)
            ->where('diaSemana', $diaSemana)
            ->where('idSesion', $idSesion)
            ->delete();

            if ($deleted === 0) {
                return response()->json(
                    ResultResponse::fail(
                        ResultResponse::NOT_FOUND_CODE,
                        ResultResponse::TXT_NOT_FOUND_CODE,
                    ),
                    ResultResponse::NOT_FOUND_CODE
                );
            }

            return response()->json(
                ResultResponse::ok(null),
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

    private function validateBloqueo(Request $request, bool $isUpdate = false): void
    {
        $rules = [
            'idRecurso' => [$isUpdate ? 'sometimes' : 'required', 'integer', 'exists:RECURSO,idRecurso'],
            'diaSemana' => [$isUpdate ? 'sometimes' : 'required', 'integer', 'between:1,7'],
            'idSesion' => [$isUpdate ? 'sometimes' : 'required', 'integer', 'exists:SESION,idSesion'],
            'motivoBloqueo' => [$isUpdate ? 'sometimes' : 'nullable', 'string'],
        ];

        $messages = [
            'idRecurso.required' => 'El recurso es obligatorio.',
            'idRecurso.integer' => 'El recurso debe ser un número.',
            'idRecurso.exists' => 'El recurso indicado no existe.',

            'diaSemana.required' => 'El día de la semana es obligatorio.',
            'diaSemana.integer' => 'El día de la semana debe ser un número.',
            'diaSemana.between' => 'El día de la semana debe estar entre 1 y 7.',

            'idSesion.required' => 'La sesión es obligatoria.',
            'idSesion.integer' => 'La sesión debe ser un número.',
            'idSesion.exists' => 'La sesión indicada no existe.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
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