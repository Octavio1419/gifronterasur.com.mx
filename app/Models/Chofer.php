<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // IMPORTANTE
use Illuminate\Notifications\Notifiable;

class Chofer extends Authenticatable
{
    use Notifiable;

    protected $table = 'choferes';
    protected $username = 'usuario_sistema';

    public function routeNotificationForMail($notification){
        return $this->correo;
    }
}
