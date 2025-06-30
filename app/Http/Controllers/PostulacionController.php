<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;
use App\Models\Postulacion;
use App\Models\Notificacion;
class PostulacionController extends Controller
{

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
        $cvFile = $request->file('cv')
        ;
        // Se obtiene el nombre original y genera uno único para almacenamiento
        $originalFilename = $cvFile->getClientOriginalName();
        $storedFilename = time() . '_' . $originalFilename;

        // Guarda el archivo con el nuevo nombre en storage/app/public/cv_postulaciones
        $cvPath = $cvFile->storeAs('cv_postulaciones', $storedFilename, 'public');

        $postulacion = Postulacion::create([
            'user_id' => $request->user()->id,
            'oferta_id' => $request->oferta_id,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'comentario' => $request->comentario,
            'cv_path' => $cvPath,
            'cv_original_name' => $originalFilename,
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
        $path = storage_path('app/public/' . $postulacion->cv_path);

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado');
        }
        $downloadName = $postulacion->cv_original_name ?? 'curriculum.pdf';

        return response()->download($path, $downloadName);
    }
    public function verificarPostulacion(Request $request, $ofertaId)
    {
        $userId = $request->user()->id;

        $existe = Postulacion::where('user_id', $userId)
            ->where('oferta_id', $ofertaId)
            ->exists();

        return response()->json(['ya_postulado' => $existe]);
    }
    public function showByUserId($userId)
    {
        $postulaciones = Postulacion::with('oferta:id,puesto,categoria,empresa')->where('user_id', $userId)->get();

        if ($postulaciones->isEmpty()) {
            return response()->json(['message' => 'No se encontraron ofertas para este usuario'], 404);
        }

        return response()->json($postulaciones);
    }
    public function showByOfertaId($ofertaId)
    {
        $postulaciones = Postulacion::with([
            'user:id,name,email',
            'oferta:id,puesto'
        ])
            ->where('oferta_id', $ofertaId)
            ->get();
        if ($postulaciones->isEmpty()) {
            return response()->json(['message' => 'No se encontraron postulaciones para esta oferta'], 404);
        }

        return response()->json($postulaciones);
    }
    public function acceptPostulacion(Request $request, $idPostulacion, $idOferta)
    {

        $postulacion = Postulacion::findOrFail($idPostulacion);
        $postulacion->estado = 'aceptada';
        $postulacion->save();

        $oferta = Oferta::findOrFail($idOferta);
        $oferta->estado = 'inactiva';
        $oferta->save();

        Notificacion::create([
            'user_id' => $postulacion->user_id,
            'tipo' => 'Ha sido aceptado',
            'mensaje' => 'Tu postulación a "' . $oferta->puesto . '" fue aceptada.',
            'leida' => false
        ]);
        return response()->json(['message' => 'Postulación aceptada correctamente.', 'data' => $postulacion]);
    }

}
