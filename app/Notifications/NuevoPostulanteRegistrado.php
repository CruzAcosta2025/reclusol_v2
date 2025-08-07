<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoPostulanteRegistrado extends Notification
{
    use Queueable;

    public $postulante;

    public function __construct($postulante)
    {
        $this->postulante = $postulante;
    }

    public function via($notifiable)
    {
        return ['database'];
    }
    
    public function toArray($notifiable)
    {
        return [
            'mensaje' => "Nuevo postulante registrado: {$this->postulante->nombres} {$this->postulante->apellidos} (DNI: {$this->postulante->dni})",
            'dni' => $this->postulante->dni,
            'nombre' => $this->postulante->nombres ?? '',
            'apellidos' => $this->postulante->apellidos ?? '',
        ];
    }
}
