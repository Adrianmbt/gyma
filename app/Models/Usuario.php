<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'cedula',
        'telefono',
        'usuario',
        'clave',
        'rol',
    ];

    protected $hidden = [
        'clave',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->clave;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'usuario';
    }
}
