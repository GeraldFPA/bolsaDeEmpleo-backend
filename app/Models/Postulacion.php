<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Postulacion extends Model
{
    use HasFactory;
 

    protected $fillable = [
        'user_id',
        'oferta_id',
        'estado',
        'nombre',
        'email',
        'telefono',
        'comentario',
        'cv_path',
        'cv_original_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class,'oferta_id');
    }
}
