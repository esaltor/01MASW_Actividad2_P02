<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class RecursoController extends Controller
{
    function validateResource(Request $request, ?int $id = null): void {
        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string', 'max:255'],
            'ubicacion' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'string', 'max:50'],
            'caracteristicas' => ['nullable', 'string'],
            'idTipoRecurso' => ['required', 'integer', 'exists:TIPORECURSO,idTipoRecurso'],
        ];
    
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar 100 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar 255 caracteres.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.max' => 'La ubicación no puede superar 100 caracteres.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.max' => 'El estado no puede superar 50 caracteres.',
            'idTipoRecurso.required' => 'El tipo de recurso es obligatorio.',
            'idTipoRecurso.integer' => 'El tipo de recurso debe ser un número.',
            'idTipoRecurso.exists' => 'El tipo de recurso indicado no existe.',
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

            // Obtención del listado de roles paginado
            $recursos = Recurso::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($recursos),
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
        $this->validateResource($request);

        try {
            $newRecurso = new Recurso([
                'nombre' => $request->get('nombre'),
                'descripcion' => $request->get('descripcion'),
                'ubicacion' => $request->get('ubicacion'),
                'estado' => $request->get('estado'),
                'caracteristicas' => $request->get('caracteristicas'),
                'idTipoRecurso' => $request->get('idTipoRecurso'),
            ]);

            $newRecurso->save();

            return response()->json(
                ResultResponse::ok($newRecurso),
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
            $recurso = Recurso::findOrFail($id);

            return response()->json(
                ResultResponse::ok($recurso),
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
            $recurso = Recurso::findOrFail($id);

            $recurso->nombre = $request->get('nombre', $recurso->nombre);
            $recurso->descripcion = $request->get('descripcion', $recurso->descripcion);
            $recurso->ubicacion = $request->get('ubicacion', $recurso->ubicacion);
            $recurso->estado = $request->get('estado', $recurso->estado);
            $recurso->caracteristicas = $request->get('caracteristicas', $recurso->caracteristicas);
            $recurso->idTipoRecurso = $request->get('idTipoRecurso', $recurso->idTipoRecurso);

            $recurso->save();

            return response()->json(
                ResultResponse::ok($recurso),
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
        $this->validateResource($request,(int) $id);

        try {
            $recurso = Recurso::findOrFail($id);

            $recurso->nombre = $request->get('nombre', $recurso->nombre);
            $recurso->descripcion = $request->get('descripcion', $recurso->descripcion);
            $recurso->ubicacion = $request->get('ubicacion', $recurso->ubicacion);
            $recurso->estado = $request->get('estado', $recurso->estado);
            $recurso->caracteristicas = $request->get('caracteristicas', $recurso->caracteristicas);
            $recurso->idTipoRecurso = $request->get('idTipoRecurso', $recurso->idTipoRecurso);

            $recurso->save();

            return response()->json(
                ResultResponse::ok($recurso),
                ResultResponse::SUCCESS_CODE
            );
        } catch(Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::NOT_FOUND_CODE,
                    ResultResponse::TXT_NOT_FOUND_CODE,
                    $e->getMessage()
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
            $recurso = Recurso::findOrFail($id);
            $recurso->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($recurso),
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
