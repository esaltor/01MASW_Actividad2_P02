<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Http\Responses\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;

class UsuarioController extends Controller
{
    /**
     * Validate role data sent by the client
     */
    function validateUser(Request $request, ?int $id = null): void
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'email' => [
                'required',
                'email',
                'max:100',
                $id === null
                ? Rule::unique('USUARIO', 'email')->whereNull('deleted_at')
                : Rule::unique('USUARIO', 'email')->ignore($id, 'idUsuario')->whereNull('deleted_at'),
            ],
            'password' => $id === null
                ? ['required', 'string', 'min:8']
                : ['nullable', 'string', 'min:8'],
            'idRol' => ['required', 'integer', 'exists:ROL,idRol'],
        ];
    
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar 100 caracteres.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.max' => 'Los apellidos no pueden superar 100 caracteres.',
            'telefono.max' => 'El teléfono no puede superar 15 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no tiene un formato válido.',
            'email.max' => 'El email no puede superar 100 caracteres.',
            'email.unique' => 'Ya existe un usuario con ese email.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'idRol.required' => 'El rol es obligatorio.',
            'idRol.integer' => 'El rol debe ser un número.',
            'idRol.exists' => 'El rol indicado no existe.',
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
     * Validate login credentials
     */
    function validateLogin(Request $request): void
    {
        $rules = [
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8'],
        ];
    
        $messages = [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no tiene un formato válido.',
            'email.max' => 'El email no puede superar 100 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
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

            // Obtención del listado de usuarios paginado
            $usuarios = Usuario::paginate($pageSize, ['*'], 'pageKey', $pageKey);

            return response()->json(
                ResultResponse::ok($usuarios),
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
     * Register an user
     */
    public function register(Request $request)
    {
        $this->validateUser($request, null);

        try {
            $usuario = new Usuario([
                'nombre' => $request->get('nombre'),
                'apellidos' => $request->get('apellidos'),
                'telefono' => $request->get('telefono'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'idRol' => Rol::where('nombre', 'Usuario')->first()->idRol, // Rol fijo de usuario sin privilegios al registrarse
            ]);

            $usuario->save();

            $token = $usuario->createToken('api')->plainTextToken;

            return response()->json(
                ResultResponse::ok([
                    'usuario' => $usuario,
                    'token' => $token,
                ]),
                ResultResponse::SUCCESS_CODE
            );
        } catch (Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    ResultResponse::TXT_INTERNAL_SERVER_ERROR_CODE
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Validate credentials and return session token
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        try {
            $usuario = Usuario::with('rol')->where('email', $request->get('email'))->first();

            if (!$usuario || !Hash::check($request->get('password'), $usuario->password)) {
                return response()->json(
                    ResultResponse::fail(
                        ResultResponse::UNAUTHORIZED_CODE,
                        ResultResponse::TXT_UNAUTHORIZED_CODE
                    ),
                    ResultResponse::UNAUTHORIZED_CODE
                );
            }

            $token = $usuario->createToken('api')->plainTextToken;

            return response()->json(
                ResultResponse::ok([
                    'usuario' => $usuario,
                    'token' => $token,
                ]),
                ResultResponse::SUCCESS_CODE
            );
        } catch (Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    ResultResponse::TXT_INTERNAL_SERVER_ERROR_CODE
                ),
                ResultResponse::INTERNAL_SERVER_ERROR_CODE
            );
        }
    }

    /**
     * Revokes current session token
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(
                ResultResponse::ok(['logout' => true]),
                ResultResponse::SUCCESS_CODE
            );
        } catch (Throwable $e) {
            return response()->json(
                ResultResponse::fail(
                    ResultResponse::INTERNAL_SERVER_ERROR_CODE,
                    ResultResponse::TXT_INTERNAL_SERVER_ERROR_CODE
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
         $this->validateUser($request);

         try {
             $newUsuario = new Usuario([
                 'nombre' => $request->get('nombre'),
                 'apellidos' => $request->get('apellidos'),
                 'telefono' => $request->get('telefono'),
                 'email' => $request->get('email'),
                 'password' => Hash::make($request->get('password')),
                 'idRol' => $request->get('idRol'),
             ]);

             $newUsuario->save();
 
             return response()->json(
                 ResultResponse::ok($newUsuario),
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
            $usuario = Usuario::findOrFail($id);

            return response()->json(
                ResultResponse::ok($usuario),
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
            $usuario = Usuario::findOrFail($id);

            $usuario->nombre = $request->get('nombre', $usuario->nombre);
            $usuario->apellidos = $request->get('apellidos', $usuario->apellidos);
            $usuario->telefono = $request->get('telefono', $usuario->telefono);
            $usuario->email = $request->get('email', $usuario->email);
            $usuario->idRol = $request->get('idRol', $usuario->idRol);

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->get('password'));
            }

            $usuario->save();

            return response()->json(
                ResultResponse::ok($usuario),
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
        $this->validateUser($request, (int) $id);

        try {
            $usuario = Usuario::findOrFail($id);

            $usuario->nombre = $request->get('nombre', $usuario->nombre);
            $usuario->apellidos = $request->get('apellidos', $usuario->apellidos);
            $usuario->telefono = $request->get('telefono', $usuario->telefono);
            $usuario->email = $request->get('email', $usuario->email);
            $usuario->idRol = $request->get('idRol', $usuario->idRol);

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->get('password'));
            }

            $usuario->save();

            return response()->json(
                ResultResponse::ok($usuario),
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
            $usuario = Usuario::findOrFail($id);
            $usuario->delete(); // No borra físicamente porque el modelo hace uso de SoftDeletes

            return response()->json(
                ResultResponse::ok($usuario),
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
