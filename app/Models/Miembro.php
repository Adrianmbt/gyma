<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Miembro extends Model
{
    use HasFactory;

    protected $table = 'miembros';
    
    // Deshabilitar timestamps automáticos de Laravel
    public $timestamps = false;
    
    // Especificar la columna de timestamp manual
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre',
        'telefono',
        'numero_cedula',
        'fecha_nacimiento',
        'ruta_foto',
        'estatus',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_registro' => 'datetime',
    ];

    public function suscripciones()
    {
        return $this->hasMany(MiembroSuscripcion::class, 'miembro_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'miembro_id');
    }

    public function suscripcionActiva()
    {
        return $this->hasOne(MiembroSuscripcion::class, 'miembro_id')
            ->orderBy('fecha_registro', 'desc');
    }

    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    public function getEstatusSuscripcionAttribute()
    {
        if ($this->estatus === 'vetado') {
            return 'Vetado';
        }

        $suscripcion = $this->suscripcionActiva;
        
        if (!$suscripcion) {
            return 'Sin Suscripción';
        }

        $fechaFin = Carbon::parse($suscripcion->fecha_fin);
        $hoy = Carbon::now();

        if ($fechaFin->isPast()) {
            return 'Vencida';
        }

        if ($fechaFin->diffInDays($hoy) <= 3) {
            return 'Por Vencer';
        }

        return 'Activo';
    }
}
