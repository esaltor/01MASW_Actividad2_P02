<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use App\Http\Responses\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class IncidenciaController extends Controller
{
    /**
     * Display a listing of the incidence.
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

            // Obtención del listado de incidencias paginado
            $incidencias = Incide::with(['tipoIncidencia', 'elemento', 'usuario'])
                ->orderBy('idIncidencia')
                ->paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($incidencias),
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
     * Store a newly created incidence in storage.
     */
    public function store(Request $request)
    {
        // Validación de los campos introducidos
        $this->validateIncidencia($request);

        try {
            $newIncidencia = new Incidencia([
                'titulo' => $request->input('titulo'),
                'descripcion' => $request->input('descripcion'),
                'estado' => $request->input('estado'),
                'idTipoIncidencia' => $request->input('idTipoIncidencia'),
                'idElemento' => $request->input('idElemento'),
                'idUsuario' => $request->input('idUsuario'),
            ]);

            $newIncidencia->save();

            return response()->json(
                ResultResponse::ok($newIncidencia),
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
     * Display the specified incidence.
     */
    public function show($id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);

            return response()->json(
                ResultResponse::ok($incidencia),
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
     * Show the form for editing the specified incidence.
     */
    public function edit(Request $request, $id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);

            $incidencia->titulo = $request->get('titulo', $incidencia->titulo);
            $incidencia->descripcion = $request->get('descripcion', $incidencia->descripcion);
            $incidencia->estado = $request->get('estado', $incidencia->estado);
            $incidencia->caracteristicas = $request->get('caracteristicas', $incidencia->caracteristicas);
            $incidencia->idTipoIncidencia = $request->get('idTipoIncidencia', $incidencia->idTipoIncidencia);

            $incidencia->save();

            return response()->json(
                ResultResponse::ok($incidencia),
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
        try {
            $incidencia = Incidencia::findOrFail($id);

            $incidencia->titulo = $request->get('titulo', $incidencia->titulo);
            $incidencia->descripcion = $request->get('descripcion', $incidencia->descripcion);
            $incidencia->estado = $request->get('estado', $incidencia->estado);
            $incidencia->caracteristicas = $request->get('caracteristicas', $incidencia->caracteristicas);
            $incidencia->idTipoIncidencia = $request->get('idTipoIncidencia', $incidencia->idTipoIncidencia);

            $incidencia->save();

            return response()->json(
                ResultResponse::ok($incidencia),
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
     * Remove the specified incidence from storage.
     */
    public function destroy($id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($incidencia),
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

    private function validateIncidencia(Request $request, ?int $id = null): void
    {
        $rules = [
            'titulo' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'max:50'],
            'idTipoIncidencia' => ['required', 'integer', 'exists:TIPOINCIDENCIA,idTipoIncidencia'],
            'idElemento' => ['required', 'integer', 'exists:ELEMENTO,idElemento'],
            'idUsuario' => ['required', 'integer', 'exists:USUARIO,idUsuario'],
        ];

        $messages = [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede superar 100 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar 255 caracteres.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.max' => 'El estado no puede superar 50 caracteres.',
            'idTipoIncidencia.required' => 'El tipo de incidencia es obligatorio.',
            'idTipoIncidencia.integer' => 'El tipo de incidencia debe ser un número.',
            'idTipoIncidencia.exists' => 'El tipo de incidencia indicado no existe.',
            'idElemento.required' => 'El elemento es obligatorio.',
            'idElemento.integer' => 'El elemento debe ser un número.',
            'idElemento.exists' => 'El elemento indicado no existe.',
            'idUsuario.required' => 'El usuario es obligatorio.',
            'idUsuario.integer' => 'El usuario debe ser un número.',
            'idUsuario.exists' => 'El usuario indicado no existe.',
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
