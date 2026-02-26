<?php

namespace App\Http\Controllers;

use App\Models\Adjunto;
use App\Models\AdjuntoIncidencia;
use App\Models\AdjuntoElemento;
use App\Models\Incidencia;
use App\Models\Elemento;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try {
            // Obtención del número de la página y del número de elementos por página
            $pageKey = (int) request()->query('pageKey', 1);
            $pageSize = (int) request()->query('pageSize', 10);

            // Límites de la paginación para evitar abusos
            $pageKey = max(1, $pageKey);
            $pageSize = min(max(1, $pageSize), 100);

            // Obtención del listado de roles paginado
            $adjuntos = Adjunto::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($adjuntos),
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
    public function storeAdjuntoIncidencia(Request $request, $idIncidencia)
    {
        // Validación de los campos introducidos
        $this->validateAttachment($request);

        try {
            $result = DB::transaction(function () use ($request, $idIncidencia){
                // Verificación de la existencia de la incidencia
                Incidencia::findOrFail($idIncidencia);

                // 1. Creación del Adjunto
                $adjunto = new Adjunto([
                    'nombre' => $request->get('nombre'),
                    'mimeType' => $request->get('mimeType'),
                    'tamBytes' => $request->get('tamBytes'),
                    'url' => $request->get('url'),
                ]);

                // Guardado del adjunto en BD
                $adjunto->save();

                // 2. Vinculación en AdjuntoIncidencia
                $link = new AdjuntoIncidencia([
                    'idAdjunto' => $adjunto->idAdjunto,
                    'idIncidencia' => (int) $idIncidencia,
                ]);

                // Guardado del enlace entre Incidencia y Adjunto
                $link->save();

                return [
                    'adjunto' => $adjunto,
                    'vinculo' => $link,
                ];
            });

            return response()->json(
                ResultResponse::ok($result),
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
    public function storeAdjuntoElemento(Request $request, $idElemento)
    {
        // Validación de los campos introducidos
        $this->validateAttachment($request);

        try {
            $result = DB::transaction(function () use ($request, $idElemento){
                // Verificación de la existencia de el elemento
                Elemento::findOrFail($idElemento);

                // 1. Creación del Adjunto
                $adjunto = new Adjunto([
                    'nombre' => $request->get('nombre'),
                    'mimeType' => $request->get('mimeType'),
                    'tamBytes' => $request->get('tamBytes'),
                    'url' => $request->get('url'),
                ]);

                // Guardado del adjunto en BD
                $adjunto->save();

                // 2. Vinculación en AdjuntoElemento
                $link = new AdjuntoElemento([
                    'idAdjunto' => $adjunto->idAdjunto,
                    'idElemento' => (int) $idElemento,
                ]);

                // Guardado del enlace entre Elemento y Adjunto
                $link->save();

                return [
                    'adjunto' => $adjunto,
                    'vinculo' => $link,
                ];
            });

            return response()->json(
                ResultResponse::ok($result),
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
            $adjunto = Adjunto::findOrFail($id);

            return response()->json(
                ResultResponse::ok($adjunto),
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
            $adjunto = Adjunto::findOrFail($id);

            $adjunto->nombre = $request->get('nombre', $adjunto->nombre);
            $adjunto->mimeType = $request->get('mimeType', $adjunto->mimeType);
            $adjunto->tamBytes = $request->get('tamBytes', $adjunto->tamBytes);
            $adjunto->url = $request->get('url', $adjunto->url);

            $adjunto->save();

            return response()->json(
                ResultResponse::ok($adjunto),
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
        $this->validateAttachment($request, (int) $id);

        try {
            $adjunto = Adjunto::findOrFail($id);

            $adjunto->nombre = $request->get('nombre', $adjunto->nombre);
            $adjunto->mimeType = $request->get('mimeType', $adjunto->mimeType);
            $adjunto->tamBytes = $request->get('tamBytes', $adjunto->tamBytes);
            $adjunto->url = $request->get('url', $adjunto->url);

            $adjunto->save();

            return response()->json(
                ResultResponse::ok($adjunto),
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
            $adjunto = Adjunto::findOrFail($id);
            $adjunto->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($adjunto),
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
