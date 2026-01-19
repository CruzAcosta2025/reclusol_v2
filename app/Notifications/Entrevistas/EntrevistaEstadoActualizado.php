<?php

namespace App\Notifications\Entrevistas;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EntrevistaEstadoActualizado extends Notification
{
    use Queueable;

    public $postulante;
    public $estadoCodigo;

    public function __construct($postulante, string $estadoCodigo)
    {
        $this->postulante = $postulante;
        $this->estadoCodigo = $estadoCodigo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $nombre = trim(($this->postulante->nombres ?? '') . ' ' . ($this->postulante->apellidos ?? ''));

        return [
            'titulo' => 'Estado de entrevista actualizado',
            'mensaje' => "Se actualizo el estado de la entrevista de {$nombre} a {$this->estadoCodigo}.",
            'tipo' => 'entrevistas',
            'nivel' => 'info',
            'icono' => 'circle-info',
            'url' => route('entrevistas.index'),
            'nombre' => $nombre,
        ];
    }
}
