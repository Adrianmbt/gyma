<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Miembro extends Model
{
    use HasFactory;

    protected $table = 'miembros';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'telefono',
        'numero_cedula',
        'fecha_nacimiento',
        'ruta_foto',
        'fecha_registro',
        'estatus',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'miembro_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'miembro_id');
    }
}
