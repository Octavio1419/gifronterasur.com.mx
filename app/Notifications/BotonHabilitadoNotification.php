<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BotonHabilitadoNotification extends Notification
{
    use Queueable;
        protected $chofer;
    
        /**
        * Create a new notification instance.
        */
        public function __construct($chofer)
        {
            $this->chofer = $chofer;
        }
    
        /**
        * Get the notification's delivery channels.
        *
        * @return array<int, string>
        */
        public function via($notifiable)
        {
            return ['mail'];
        }
    
        /**
        * Get the mail representation of the notification.
        */
        public function toMail($notifiable)
        {
            return (new MailMessage)   
                ->subject('✅ Botón de Combustible Habilitado - ' . $this->chofer->nombre) 
                ->view('emails.boton_habilitado', [
                    'chofer' => $this->chofer,
                ]);
        }
    
        /**
        * Get the array representation of the notification.
        *
        * @return array<string, mixed>
        */
        public function toArray($notifiable)
        {
            return [
                //
            ];
        }

}
