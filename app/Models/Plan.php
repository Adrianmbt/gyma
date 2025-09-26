<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    public $timestamps = false;

    protected $fillable = [
        'nombre_plan',
        'descripcion',
        'precio_base',
        'duracion_dias',
        'estatus',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'plan_id');
    }
}
