<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AutCombustibleNotification extends Notification
{
    use Queueable;

    protected $solicitud;
    protected $chofer;

    public function __construct($solicitud, $chofer)
    {
        $this->solicitud = (object) $solicitud;
        $this->chofer = $chofer;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
      
        $vehiculoInfo = \DB::table('vehiculos_panel')->where('id', $this->solicitud->vehiculo_id)->first();

        $nombre_carro = $vehiculoInfo 
            ? $vehiculoInfo->marca . ' ' . $vehiculoInfo->linea . ' (' . $vehiculoInfo->modelo . ')' 
            : 'Vehículo no identificado';

        return (new MailMessage)
            ->subject('✅ Combustible Pre-Autorizado - Folio #' . $this->solicitud->id)
            ->view('emails.autorizado_combustible', [
                'chofer_nombre' => $this->chofer->nombre,
                'solicitud'     => $this->solicitud,
                'nombre_carro'  => $nombre_carro, 
                'efecticard'    => $this->solicitud->folio_autorizacion,
                'notas'         => $this->solicitud->notas_admin ?? 'Sin observaciones adicionales'
            ]);
    }
}