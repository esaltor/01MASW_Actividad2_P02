<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Iluminate\Validation\Rule;
use Iluminate\Http\Exceptions\HttpResponseException;
use Throwable;

class RolController extends Controller
{
    /**
     * Validate role data
     */
    function validateRol(Request $request, ?int $id = null): void {
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:50',
                $id === null
                    ? Rule::unique('ROL', 'nombre')
                    : Rule::unique('ROL', 'nombre')->ignore($id, 'idRol'),
            ],
            'descripcion' => ['required', 'string', 'max:255'],
        ];
    
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar 50 caracteres.',
            'nombre.unique' => 'Ya existe un rol con ese nombre.',
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

            // Obtención del listado de roles paginado
            $roles = Rol::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($roles),
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
        $this->validateRol($request);

        try {
            $newRol = new Rol([
                'nombre' => $request->get('nombre'),
                'descripcion' => $request->get('descripcion'),
            ]);

            $newRol->save();

            return response()->json(
                ResultResponse::ok($newRol),
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
            $rol = Rol::findOrFail($id);

            return response()->json(
                ResultResponse::ok($rol),
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
            $rol = Rol::findOrFail($id);

            $rol->nombre = $request->get('nombre', $rol->nombre);
            $rol->descripcion = $request->get('descripcion', $rol->descripcion);

            $rol->save();

            return response()->json(
                ResultResponse::ok($rol),
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
        $this->validateRol($request, (int) $id);

        try {
            $rol = Rol::findOrFail($id);

            $rol->nombre = $request->get('nombre');
            $rol->descripcion = $request->get('descripcion');

            $rol->save();

            return response()->json(
                ResultResponse::ok($rol),
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
            $rol = Rol::findOrFail($id);
            $rol->delete();

            return response()->json(
                ResultResponse::ok($rol),
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
