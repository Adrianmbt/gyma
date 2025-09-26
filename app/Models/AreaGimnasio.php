<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaGimnasio extends Model
{
    use HasFactory;

    protected $table = 'areas_gimnasio';

    public $timestamps = false;

    protected $fillable = [
        'nombre_area',
        'ubicacion',
    ];

    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'id_area');
    }
}
