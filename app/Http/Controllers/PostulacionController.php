<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulacion;
class PostulacionController extends Controller
{
    public function index()
    {

    }
    public function store(Request $request)
    {
        $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'nombre' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'comentario' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf|max:2048',
        ]);

        $cvPath = $request->file('cv')->store('cv_postulaciones', 'public');

        $postulacion = Postulacion::create([
            'user_id' => $request->user()->id,
            'oferta_id' => $request->oferta_id,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'comentario' => $request->comentario,
            'cv_path' => $cvPath,
        ]);

        return response()->json([
            'message' => 'Postulación enviada correctamente.',
            'data' => $postulacion
        ], 201);
    }
    public function recibidas(Request $request)
    {
        $user = $request->user();

        // Traer todas las postulaciones a ofertas creadas por el usuario ofertante
        $postulaciones = Postulacion::whereHas('oferta', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['user', 'oferta'])->get();

        return response()->json($postulaciones);
    }
    public function descargarCV($id)
    {
        $postulacion = Postulacion::findOrFail($id);
        $path = storage_path('app/' . $postulacion->cv);

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($path);
    }

    //
}
