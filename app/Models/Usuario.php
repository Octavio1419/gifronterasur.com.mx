<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Usuario extends Authenticatable
{
    
    protected $table = 'usuarios';
    use Notifiable;

    protected $fillable = [
        'nombre_sistema', 'password', 'tipo_rol', 'correo'
    ];

    public function routeNotificationForMail($notification)
    {
        return $this->correo; 
    }
}




