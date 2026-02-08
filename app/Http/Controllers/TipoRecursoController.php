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
    public function show(TipoRecurso $tipoRecurso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoRecurso $tipoRecurso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoRecurso $tipoRecurso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoRecurso $tipoRecurso)
    {
        //
    }
}
