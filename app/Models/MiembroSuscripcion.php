<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroSuscripcion extends Model
{
    use HasFactory;

    protected $table = 'miembro_suscripciones';

    public $timestamps = false;

    protected $fillable = [
        'miembro_id',
        'plan_id',
        'promocion_id',
        'entrenador_id',
        'fecha_inicio',
        'fecha_fin',
        'monto_pagado',
        'metodo_pago',
        'referencia_pago',
        'fecha_registro',
    ];

    public function miembro()
    {
        return $this->belongsTo(Miembro::class, 'miembro_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function promocion()
    {
        return $this->belongsTo(Promocion::class, 'promocion_id');
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'entrenador_id');
    }
}
