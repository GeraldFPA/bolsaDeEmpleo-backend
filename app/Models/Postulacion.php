<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'oferta_id', 'mensaje'
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
