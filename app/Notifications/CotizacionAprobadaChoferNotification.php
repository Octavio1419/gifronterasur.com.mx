<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CotizacionAprobadaChoferNotification extends Notification
{
    use Queueable;

    protected $solicitud;
    protected $chofer;
    protected $nombre_carro;

    // Recibimos los datos en el constructor
    public function __construct($solicitud, $chofer, $nombre_carro)
    {
        $this->solicitud = $solicitud;
        $this->chofer = $chofer;
        $this->nombre_carro = $nombre_carro;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ REPARACIÓN AUTORIZADA - Folio #' . $this->solicitud->id)
            ->view('emails.cotizacion_aprobada_chofer', [
                'chofer_nombre' => $this->chofer->nombre,
                'solicitud'     => $this->solicitud,
                'nombre_carro'  => $this->nombre_carro,
            ]);
    }
}