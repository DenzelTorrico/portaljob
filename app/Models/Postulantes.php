<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulantes extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'numero_documento', 'tipo_documento', 'nombres', 'apellidos', 'fecha_nacimiento'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
