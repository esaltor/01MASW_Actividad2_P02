<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;

class SesionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sesiones = Sesion::all();

        return response()->json($sesiones, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'horaInicio' => 'required|date_format:H:i:s',
            'horaFin' => 'required|date_format:H:i:s'
        ]);

        $sesion = Sesion::create([
            'horaInicio' => $request->horaInicio,
            'horaFin' => $request->horaFin
        ]);

        return response()->json([
            'message' => 'Sesion creada correctamente',
            'data' => $sesion
        ], 201);

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $sesion = Sesion::find($id);

        if (!$sesion) {

            return response()->json([
                'message' => 'Sesion no encontrada'
            ], 404);

        }

        return response()->json($sesion, 200);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $sesion = Sesion::find($id);

        if (!$sesion) {

            return response()->json([
                'message' => 'Sesion no encontrada'
            ], 404);

        }


        $request->validate([
            'horaInicio' => 'required|date_format:H:i:s',
            'horaFin' => 'required|date_format:H:i:s'
        ]);


        $sesion->update([
            'horaInicio' => $request->horaInicio,
            'horaFin' => $request->horaFin
        ]);


        return response()->json([
            'message' => 'Sesion actualizada correctamente',
            'data' => $sesion
        ], 200);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $sesion = Sesion::find($id);

        if (!$sesion) {

            return response()->json([
                'message' => 'Sesion no encontrada'
            ], 404);

        }


        $sesion->delete();


        return response()->json([
            'message' => 'Sesion eliminada correctamente'
        ], 200);

    }


}