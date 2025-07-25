<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class OfertaController extends Controller
{
    public function index()
    {
        $ofertas = Oferta::with('user:id,name')->where('estado', 'activo')->get();
        return response()->json($ofertas);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'puesto' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'empresa' => 'required|string|max:255',
            'horario' => 'required|string|max:255',
            'sueldo' => 'required|numeric',
            'contrato' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $oferta = Oferta::create([
            'user_id' => auth()->id(),
            'puesto' => $request->puesto,
            'categoria' => $request->categoria,
            'empresa' => $request->empresa,
            'horario' => $request->horario,
            'sueldo' => $request->sueldo,
            'contrato' => $request->contrato,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json($oferta, 201);
    }
    public function showByUserId($userId)
    {
        $ofertas = Oferta::with('user:id,name')->where('user_id', $userId)->get();
        
        if ($ofertas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron ofertas para este usuario'], 404);
        }
        
        return response()->json($ofertas);
    }

}
