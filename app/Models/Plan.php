<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';
    
    // Deshabilitar timestamps automáticos
    public $timestamps = false;

    protected $fillable = [
        'nombre_plan',
        'descripcion',
        'precio_base',
        'duracion_dias',
        'estatus',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'duracion_dias' => 'integer',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'plan_id');
    }
}
