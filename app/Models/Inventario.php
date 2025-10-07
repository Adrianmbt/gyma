<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';
    
    // Deshabilitar timestamps automáticos
    public $timestamps = false;

    protected $fillable = [
        'codigo_item',
        'nombre_item',
        'descripcion',
        'tipo',
        'departamento',
        'stock',
        'precio',
        'id_area',
        'estado',
        'fecha_adquisicion',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'fecha_adquisicion' => 'date',
    ];

    public function area()
    {
        return $this->belongsTo(AreaGimnasio::class, 'id_area');
    }

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class, 'inventario_id');
    }
}
