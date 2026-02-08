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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Recurso $recurso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recurso $recurso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recurso $recurso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recurso $recurso)
    {
        //
    }
}
