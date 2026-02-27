<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use App\Http\Responses\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class NotificacionController
{
    /**
     * Display a listing of the notification.
     */
    public function index(Request $request)
    {
        try {
            $query = Notificacion::query();

            // Filtro usuario
            if ($request->filled('idUsuario')) {
                $query->where('idUsuario', $request->idUsuario);
            }

            // Filtro fecha desde
            if ($request->filled('fecha_desde')) {
                $query->whereDate('enviadaEn', '>=', $request->fecha_desde);
            }

            // Filtro fecha hasta
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('enviadaEn', '<=', $request->fecha_hasta);
            }

            // Ordenación antes de paginar
            $query->orderBy('enviadaEn', 'desc');

            // Paginación segura
            $pageKey = (int) $request->query('pageKey', 1);
            $pageSize = (int) $request->query('pageSize', 10);

            $pageKey = max(1, $pageKey);
            $pageSize = min(max(1, $pageSize), 100);

            $notificaciones = $query->paginate(
                $pageSize,
                ['*'],
                'pageKey',
                $pageKey
            );

            return response()->json(
                ResultResponse::ok($notificaciones),
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
     * Store a newly created notification in storage.
     */
    public function store(Request $request)
    {

        // Validación de los campos introducidos
        $this->validateNotificacion($request);

        try {
            $newNotificacion = new Notificacion([
                'asunto' => $request->input('asunto'),
                'cuerpo' => $request->input('cuerpo'),
                'canal' => $request->input('canal'),
                'enviadaEn' => $request->input('enviadaEn'),
                'idUsuario' => $request->input('idUsuario'),
            ]);

            $newNotificacion->save();

            return response()->json(
                ResultResponse::ok($newNotificacion),
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
     * Display the specified notification.
     */
    public function show($id)
    {
        try {
            $notificacion = Notificacion::findOrFail($id);

            return response()->json(
                ResultResponse::ok($notificacion),
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
     * Show the form for editing the specified notification.
     */
    public function edit(Request $request, $id)
    {
        try {
            $notificacion = Notificacion::with(['usuario'])
            ->findOrFail($id);

            $notificacion->asunto = $request->get('asunto', $notificacion->asunto);
            $notificacion->cuerpo = $request->get('cuerpo', $notificacion->cuerpo);
            $notificacion->canal = $request->get('canal', $notificacion->canal);
            $notificacion->enviadaEn = $request->get('enviadaEn', $notificacion->enviadaEn);
            $notificacion->idUsuario = $request->get('idUsuario', $notificacion->idUsuario);

            $notificacion->save();

            return response()->json(
                ResultResponse::ok($notificacion),
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
     * Update the specified notification in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $notificacion = Notificacion::with(['usuario'])
            ->findOrFail($id);

            $this->validateNotificacion($request, $id);

            $notificacion->update($request->only([
                'asunto',
                'cuerpo',
                'canal',
                'enviadaEn',
                'idUsuario'
            ]));

            return response()->json(
                ResultResponse::ok($notificacion),
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
     * Remove the specified notification from storage.
     */
    public function destroy($id)
    {
        try {
            $notificacion = Notificacion::findOrFail($id);
            $notificacion->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($notificacion),
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

    private function validateNotificacion(Request $request, ?int $id = null): void
    {
        $rules = [
            'asunto' => ['sometimes', 'string', 'max:255'],
            'cuerpo' => ['sometimes', 'string'],
            'canal' => ['sometimes', 'string', 'max:50'],
            'enviadaEn' => ['sometimes', 'date'],
            'idUsuario' => ['sometimes', 'integer', 'exists:USUARIO,idUsuario'],
        ];

        $messages = [
            'asunto.string' => 'El asunto debe ser texto.',
            'asunto.max' => 'El asunto no puede superar 255 caracteres.',

            'cuerpo.string' => 'El cuerpo debe ser texto.',

            'canal.string' => 'El canal debe ser texto.',
            'canal.max' => 'El canal no puede superar 50 caracteres.',

            'enviadaEn.date' => 'La fecha de envío debe ser válida.',

            'idUsuario.integer' => 'El usuario debe ser un número.',
            'idUsuario.exists' => 'El usuario indicado no existe.',
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

}
