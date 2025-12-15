<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostulanteEnListaNegra extends Notification
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
        $nombreCompleto = trim(($this->postulante->nombres ?? '') . ' ' . ($this->postulante->apellidos ?? ''));
        $dni = $this->postulante->dni ?? '-';

        return [
            'titulo'   => 'Alerta: Postulante en lista negra',
            'mensaje'  => "El postulante {$nombreCompleto} (DNI: {$dni}) figura en la lista negra.",
            'tipo'     => 'postulantes',
            'nivel'    => 'urgente',
            'icono'    => 'shield-alert',

            'ref_id'   => $this->postulante->id ?? null,
            'url'      => ($this->postulante->id ?? null)
                ? route('postulantes.show', $this->postulante->id)
                : null,

            'dni'      => $dni,
            'nombre'   => $this->postulante->nombres ?? '',
            'apellidos' => $this->postulante->apellidos ?? '',
        ];
    }
}
