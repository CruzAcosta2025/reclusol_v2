<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostulanteEnListaNegra extends Notification
{
    use Queueable;

    public $postulante;

    /**
     * Create a new notification instance.
     */
    public function __construct($postulante)
    {
        $this->postulante = $postulante;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'mensaje' => "El postulante registrado con DNI {$this->postulante->dni} y nombre {$this->postulante->nombres} está en la lista negra.",
            'dni' => $this->postulante->dni,
            'nombre' => $this->postulante->nombres ?? '',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }
}
