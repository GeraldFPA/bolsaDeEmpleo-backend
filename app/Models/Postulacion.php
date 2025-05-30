<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Postulacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'oferta_id', 'estado','mensaje'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }
}
