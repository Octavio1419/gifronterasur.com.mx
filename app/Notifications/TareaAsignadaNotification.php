<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TareaAsignadaNotification extends Notification
{
    use Queueable;

 
    protected $tareaId;
    protected $nombre;
    protected $apellido_p;
    protected $name_task_asing;
    protected $foto_perfil;

    public function __construct($tareaId,$nombre,$apellido_p,$name_task_asing,$foto_perfil)
    {
        $this->tareaId = $tareaId;
        $this->nombre = $nombre;
        $this->apellido_p = $apellido_p;
        $this->name_task_asing = $name_task_asing;
        $this->foto_perfil = $foto_perfil;
  
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo' => $this->nombre. ' ' . $this->apellido_p.', te asigno una tarea',
            'mensaje' => 'Se te ha asignado la tarea #' . $this->tareaId,
            'tarea_id' => $this->tareaId,  
            'name_task_asing' => $this->name_task_asing,          
            'nombre_asignador'=>$this->nombre,
            'apellido_p_asignador'=>$this->apellido_p,
            'foto_perfil_asignador' => $this->foto_perfil
        ];
    }

}


