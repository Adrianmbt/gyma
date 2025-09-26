<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    public $timestamps = false;

    protected $fillable = [
        'miembro_id',
        'fecha_venta',
        'total_venta',
        'metodo_pago',
        'referencia_pago',
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
