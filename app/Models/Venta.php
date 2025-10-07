<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    
    // Deshabilitar timestamps automáticos
    public $timestamps = false;
    
    // Especificar la columna de timestamp manual
    const CREATED_AT = 'fecha_venta';
    const UPDATED_AT = null;

    protected $fillable = [
        'miembro_id',
        'total_venta',
        'metodo_pago',
        'referencia_pago',
        'fecha_venta',
    ];

    protected $casts = [
        'total_venta' => 'decimal:2',
        'fecha_venta' => 'datetime',
    ];

    public function miembro()
    {
        return $this->belongsTo(Miembro::class, 'miembro_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }
}
