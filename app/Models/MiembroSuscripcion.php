<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroSuscripcion extends Model
{
    use HasFactory;

    protected $table = 'miembro_suscripciones';
    
    // Deshabilitar timestamps automáticos de Laravel
    public $timestamps = false;
    
    // Especificar la columna de timestamp manual
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

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

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'monto_pagado' => 'decimal:2',
        'fecha_registro' => 'datetime',
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
