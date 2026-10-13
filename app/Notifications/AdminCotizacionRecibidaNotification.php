<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class AdminCotizacionRecibidaNotification extends Notification
{
    use Queueable;

    protected $incidencia;
    protected $chofer;
    protected $vehiculo;
    protected $ruta;

    // Recibimos los datos desde el controlador
    public function __construct($incidencia, $chofer, $vehiculo, $ruta)
    {
        $this->incidencia = $incidencia;
        $this->chofer = $chofer;
        $this->vehiculo = $vehiculo;
        $this->ruta = $ruta;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $nombre_carro = $this->vehiculo->marca . ' ' . $this->vehiculo->linea;

        $email = (new MailMessage)
            ->subject('✅ NUEVA COTIZACION RECIBIDA POR EL CHOFER ' .$this->chofer->nombre. ' '.$this->chofer->apellido_p)
            ->view('emails.admin_cotizacion_nueva', [ 
                'incidencia' => $this->incidencia,
                'chofer'     => $this->chofer,
                'vehiculo'   => $this->vehiculo,
                'nombre_carro' => $nombre_carro
            ]);

        // Usamos storage_path para ir a la segura con la ruta física
        $rutaFisica = storage_path('app/public/' . $this->ruta);

        if (file_exists($rutaFisica)) {
            $email->attach($rutaFisica, [
                'as' => 'Cotizacion_Reporte_'.$this->incidencia->id.'.'.pathinfo($this->ruta, PATHINFO_EXTENSION),
                'mime' => \Illuminate\Support\Facades\File::mimeType($rutaFisica),
            ]);
        }

        return $email;
    }
}