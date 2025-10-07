<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    use HasFactory;

    protected $table = 'entrenadores';
    
    // Deshabilitar timestamps automáticos
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

    protected $casts = [
        'costo_mensual' => 'decimal:2',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'entrenador_id');
    }
}
