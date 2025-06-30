<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo', // Ej: 'postulacion_aceptada'
        'mensaje',
        'leida', // banderilla
    ];
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
