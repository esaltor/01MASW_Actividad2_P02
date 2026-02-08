<?php

namespace App\Http\Controllers;

use App\Models\Adjunto;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class AdjuntoController extends Controller
{
    function validateAttachment(Request $request, ?int $id = null): void {
        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'mimeType' => ['required', 'string', 'max:100'],
            'tamBytes' => ['required', 'integer', 'min:1'],
            'url' => ['required', 'string', 'max:255'],
        ];
    
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar 100 caracteres.',
            'mimeType.required' => 'El tipo MIME es obligatorio.',
            'mimeType.max' => 'El tipo MIME no puede superar 100 caracteres.',
            'tamBytes.required' => 'El tamaño en bytes es obligatorio.',
            'tamBytes.integer' => 'El tamaño en bytes debe ser un número.',
            'tamBytes.min' => 'El tamaño en bytes debe ser mayor que 0.',
            'url.required' => 'La URL es obligatoria.',
            'url.max' => 'La URL no puede superar 255 caracteres.',
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
    public function show(Adjunto $adjunto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adjunto $adjunto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adjunto $adjunto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adjunto $adjunto)
    {
        //
    }
}
