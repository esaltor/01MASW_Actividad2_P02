<?php

namespace App\Http\Controllers;

use App\Models\TipoRecurso;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class TipoRecursoController extends Controller
{
    function validateResourceType(Request $request, ?int $id = null): void {
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:100',
                $id === null
                    ? Rule::unique('TIPORECURSO', 'nombre')->whereNull('deleted_at')
                    : Rule::unique('TIPORECURSO', 'nombre')->ignore($id, 'idTipoRecurso')->whereNull('deleted_at'),
            ],
            'descripcion' => ['required', 'string', 'max:255'],
        ];
    
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar 100 caracteres.',
            'nombre.unique' => 'Ya existe un tipo de recurso con ese nombre.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar 255 caracteres.',
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

            // Obtención del listado paginado de tipos de recurso
            $tipos = TipoRecurso::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($tipos),
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
        $this->validateResourceType($request);

        try {
            $newTipoRecurso = new TipoRecurso([
                'nombre' => $request->get('nombre'),
                'descripcion' => $request->get('descripcion'),
            ]);

            $newTipoRecurso->save();

            return response()->json(
                ResultResponse::ok($newTipoRecurso),
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
            $tipoRecurso = TipoRecurso::findOrFail($id);

            return response()->json(
                ResultResponse::ok($tipoRecurso),
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
            $tipoRecurso = TipoRecurso::findOrFail($id);

            $tipoRecurso->nombre = $request->get('nombre', $tipoRecurso->nombre);
            $tipoRecurso->descripcion = $request->get('descripcion', $tipoRecurso->descripcion);

            $tipoRecurso->save();

            return response()->json(
                ResultResponse::ok($tipoRecurso),
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
        $this->validateResourceType($request, (int) $id);

        try {
            $tipoRecurso = TipoRecurso::findOrFail($id);

            $tipoRecurso->nombre = $request->get('nombre');
            $tipoRecurso->descripcion = $request->get('descripcion');

            $tipoRecurso->save();

            return response()->json(
                ResultResponse::ok($tipoRecurso),
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
            $tipoRecurso = TipoRecurso::findOrFail($id);
            $tipoRecurso->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($tipoRecurso),
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
