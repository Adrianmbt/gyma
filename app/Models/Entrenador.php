<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    use HasFactory;

    protected $table = 'entrenadores';

    public $timestamps = false;

    protected $fillable = [
        'nombre_completo',
        'numero_cedula',
        'telefono',
        'email',
        'especialidad',
        'costo_mensual',
        'estatus',
        'ruta_foto',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'entrenador_id');
    }
}
