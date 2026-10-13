<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SolicitudPlazoVencidoiNotification extends Notification
{
    use Queueable;

    protected $solicitud;
    protected $chofer;

    /**
     * Create a new notification instance.
     */
    public function __construct($solicitud, $chofer)
    {
        $this->solicitud = $solicitud;
        $this->chofer = $chofer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
       return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $id_vehiculo = $this->chofer['vehiculo_id'] ?? null;
        $vehiculoInfo = \App\Models\Vehiculo::find($id_vehiculo);
        $nombre_carro = $vehiculoInfo 
            ? $vehiculoInfo->marca . ' ' . $vehiculoInfo->linea . ' (' . $vehiculoInfo->modelo . ')' 
            : 'Vehículo no identificado';

        return (new MailMessage)   
            ->subject('⚠️ Solicitud Fuera de Plazo - ' . $this->chofer->nombre) 
            ->view('emails.solicitudes_plazo_vencido', [
                'admin_nombre' => $notifiable->nombre,
                'chofer'       => $this->chofer,
                'solicitud'    => $this->solicitud,
                'nombre_carro' => $nombre_carro,
                'notas'        => $this->solicitud['motivo_carga'] ?? 'Sin motivo adicional'
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
