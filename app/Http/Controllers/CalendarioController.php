<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    /**
     * Valida el body para marcar/desmarcar lectivo.
     * Si no viene 'lectivo', se interpreta como "toggle".
     */
    private function validateCalendarioToggle(Request $request): void
    {
        $rules = [
            'lectivo' => ['nullable', 'boolean'],
        ];

        $messages = [
            'lectivo.boolean' => 'El campo lectivo debe ser booleano.',
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
     * GET /calendario
     * Devuelve todos los días.
     */
    public function index()
    {
        try {
            // Si quieres ordenar siempre por fecha:
            $dias = Calendario::orderBy('fecha')->get();

            return response()->json(
                ResultResponse::ok($dias),
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
        $this->validateCalendarioToggle($request);

        try {
            $newRecurso = new Calendario([
                'fecha' => $request->get('fecha'),
                'lectivo' => $request->get('lectivo')
            ]);

            $newRecurso->save();

            return response()->json(
                ResultResponse::ok($newRecurso),
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
    public function edit(Request $request, $fecha)
    {
        try {
            $calendario = Calendario::findOrFail($fecha);

            $calendario->fecha = $request->get('nombre', $calendario->fecha);
            $calendario->lectivo = $request->get('descripcion', $calendario->lectivo);

            $calendario->save();

            return response()->json(
                ResultResponse::ok($calendario),
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
    public function update(Request $request, $fecha)
    {
        // Validación de los campos introducidos
        $this->validateCalendarioToggle($request,(int) $fecha);

        try {
            $calendario = Calendario::findOrFail($fecha);

            $calendario->fecha = $request->get('nombre', $calendario->fecha);
            $calendario->lectivo = $request->get('descripcion', $calendario->lectivo);

            $calendario->save();

            return response()->json(
                ResultResponse::ok($calendario),
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
    public function destroy($fecha)
    {
        try {
            $calendario = Calendario::findOrFail($fecha);
            $calendario->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($calendario),
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
     * Display the specified resource.
     */
    public function show($fecha)
    {
        try {
            $calendario = Calendario::findOrFail($fecha);

            return response()->json(
                ResultResponse::ok($calendario),
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
     * PUT /calendario/{fecha}
     * Body opcional: { "lectivo": true|false }
     * Si no se envía lectivo, hace toggle.
     *
     * Reglas:
     * - La fecha debe existir en la tabla
     * - No se puede cambiar si es sábado o domingo
     */
    public function toggleLectivo(Request $request, string $fecha)
    {
        // Validación del body
        $this->validateCalendarioToggle($request);

        // Validación de la fecha (path param)
        try {
            $date = Carbon::createFromFormat('Y-m-d', $fecha)->startOfDay();
        } catch (Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::ERROR_CODE,
                    ResultResponse::TXT_ERROR_CODE,
                    ['fecha' => ['La fecha debe tener el formato YYYY-MM-DD.']]
                ),
                ResultResponse::ERROR_CODE
            );
        }

        // Prohibir sábados y domingos
        if ($date->isWeekend()) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::ERROR_CODE,
                    ResultResponse::TXT_ERROR_CODE,
                    ['fecha' => ['No se puede modificar un sábado o domingo.']]
                ),
                ResultResponse::ERROR_CODE
            );
        }

        try {
            $dia = Calendario::findOrFail($fecha); // PK = fecha

            // Si viene lectivo, lo ponemos; si no, alternamos
            if ($request->has('lectivo')) {
                $dia->lectivo = (bool) $request->input('lectivo');
            } else {
                $dia->lectivo = !$dia->lectivo;
            }

            $dia->save();

            return response()->json(
                ResultResponse::ok($dia),
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
}