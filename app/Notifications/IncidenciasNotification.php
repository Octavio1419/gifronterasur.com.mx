<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncidenciasNotification extends Notification
{
    use Queueable;

    protected $incidencia;
    protected $chofer;
    /**
     * Create a new notification instance.
     */
    public function __construct($incidencia, $chofer)
    {
        $this->incidencia = $incidencia;
        $this->chofer = $chofer;
    }
  

    public function via($notifiable){
        // Mantenemos database y mail
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $vehiculoInfo = \App\Models\Vehiculo::find($this->incidencia['vehiculo_id']);

        $nombre_carro = $vehiculoInfo 
            ? $vehiculoInfo->marca . ' ' . $vehiculoInfo->linea . ' (' . $vehiculoInfo->modelo . ')' 
            : 'Vehículo no identificado';

        return (new MailMessage)
            ->subject('❗❗ Nueva Incidencia Reportada - ' . $this->chofer->nombre . ' ❗❗')
            ->view('emails.incidencias', [
                'admin_nombre' => $notifiable->nombre,
                'chofer'       => $this->chofer,
                'incidencia'   => $this->incidencia,
                'nombre_carro' => $nombre_carro, 
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
