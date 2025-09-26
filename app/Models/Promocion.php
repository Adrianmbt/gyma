<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    public $timestamps = false;

    protected $fillable = [
        'nombre_promo',
        'descripcion',
        'tipo_descuento',
        'valor_descuento',
        'aplica_a',
        'condicion_personas',
        'fecha_inicio',
        'fecha_fin',
        'estatus',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'promocion_id');
    }
}
