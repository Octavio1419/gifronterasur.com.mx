<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class SolicitudCombustibleNotification extends Notification
{
   use Queueable;

    protected $solicitud;
    protected $chofer;

    public function __construct($solicitud, $chofer)
    {
        $this->solicitud = $solicitud;
        $this->chofer = $chofer;
    }

    public function via($notifiable)
    {
        // Mantenemos database y mail
        return ['database', 'mail'];
    }

   public function toMail($notifiable){
        // Buscamos el vehículo usando el ID que viene en la solicitud
        $vehiculoInfo = \App\Models\Vehiculo::find($this->solicitud['vehiculo_id']);

        $nombre_carro = $vehiculoInfo 
            ? $vehiculoInfo->marca . ' ' . $vehiculoInfo->linea . ' (' . $vehiculoInfo->modelo . ')' 
            : 'Vehículo no identificado';

        return (new MailMessage)
            ->subject('⚠️ Nueva Solicitud de Combustible - ' . $this->chofer->nombre)
            ->view('emails.solicitud_combustible', [
                'admin_nombre' => $notifiable->nombre,
                'chofer'       => $this->chofer,
                'solicitud'    => $this->solicitud,
                'nombre_carro' => $nombre_carro, 
                'efecticard'   => $vehiculoInfo->efecticard ?? 'N/A', 
                'notas'        => $this->solicitud['notas_chofer'] ?? 'Sin notas adicionales'
            ]);
    }
    /**
     * Usamos toArray o toDatabase para unificar con Tareas
     */
    public function toArray($notifiable){
        return [
           
            'titulo' => $this->chofer->nombre. ' '. $this->chofer->apellido_p . ', Solicito combustible',
            'mensaje' => $this->chofer->nombre . ' solicita $' . $this->solicitud['monto_solicitado'] . ' para el vehículo #' . $this->solicitud['vehiculo_id'],
            'name_task_asing' => 'Carga de combustible', 
            'tipo' => 'combustible',
            'monto' => $this->solicitud['monto_solicitado'],
            'chofer_id' => $this->chofer->id,
            'nombre_asignador' => $this->chofer->nombre, 
            'foto_perfil_asignador' => $this->chofer->foto_chofer, 
            'solicitud_id' => $this->solicitud['id'] ?? null 
        ];
    }
}
