<?php

namespace App\Http\Controllers;

use App\Models\Elemento;
use Illuminate\Http\Request;
use App\Http\Responses\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class ElementoController
{
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

            // Obtención del listado de elementos paginado
            $elementos = Elemento::with('recurso')
                ->orderBy('idElemento')
                ->paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($elementos),
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
     * Store a newly created element in storage.
     */
    public function store(Request $request)
    {
        // Validación de los campos introducidos
        $this->validateElemento($request);

        try {
            $newElemento = new Elemento([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'estado' => $request->input('estado'),
                'idRecurso' => $request->input('idRecurso'),
            ]);

            $newElemento->save();

            return response()->json(
                ResultResponse::ok($newElemento),
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
     * Display the specified element.
     */
    public function show($id)
    {
        try {
            $elemento = Elemento::findOrFail($id);

            return response()->json(
                ResultResponse::ok($elemento),
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
     * Show the form for editing the specified element.
     */
    public function edit(Request $request, $id)
    {
        try {
            $elemento = Elemento::findOrFail($id);

            $elemento->nombre = $request->get('nombre', $elemento->nombre);
            $elemento->descripcion = $request->get('descripcion', $elemento->descripcion);
            $elemento->estado = $request->get('estado', $elemento->estado);
            $elemento->idRecurso = $request->get('idRecurso', $elemento->idRecurso);

            $elemento->save();

            return response()->json(
                ResultResponse::ok($elemento),
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
     * Update the specified element in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $elemento = Elemento::findOrFail($id);

            $elemento->nombre = $request->get('nombre', $elemento->nombre);
            $elemento->descripcion = $request->get('descripcion', $elemento->descripcion);
            $elemento->estado = $request->get('estado', $elemento->estado);
            $elemento->idRecurso = $request->get('idRecurso', $elemento->idRecurso);

            $elemento->save();

            return response()->json(
                ResultResponse::ok($elemento),
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
            $elemento = Elemento::findOrFail($id);
            $elemento->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($elemento),
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

    private function validateElemento(Request $request, ?int $id = null): void
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'max:50'],
            'idRecurso' => ['nullable', 'integer', 'exists:RECURSO,idRecurso'],
        ];

        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar 100 caracteres.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede superar 255 caracteres.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.string' => 'El estado debe ser texto.',
            'estado.max' => 'El estado no puede superar 50 caracteres.',

            'idRecurso.integer' => 'El recurso debe ser un número.',
            'idRecurso.exists' => 'El recurso indicado no existe.',
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
