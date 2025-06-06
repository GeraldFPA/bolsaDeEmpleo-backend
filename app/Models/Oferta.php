<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oferta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'puesto',
        'categoria',
        'empresa',
        'horario',
        'sueldo',
        'contrato',
        'estado',
        'descripcion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'oferta_id');
    }

}
