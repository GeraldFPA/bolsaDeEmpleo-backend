<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;

class NotificacionesController extends Controller
{

    public function getNotificacionesNoLeidas(Request $request)
    {
        $user = $request->user();
        $notificaciones = Notificacion::where('user_id', $user->id)
            ->where('leida', false)
            ->get();

        return response()->json($notificaciones);
    }
    public function marcarComoLeidas(Request $request)
    {
        $user = $request->user();

        Notificacion::where('user_id', $user->id)
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['message' => 'Notificaciones marcadas como leídas']);
    }
    public function getNotificaciones(Request $request)
    {
        $user = $request->user();
        $notificaciones = Notificacion::where('user_id', $user->id)->get();

        return response()->json($notificaciones);
    }


}
