<?php

namespace App\Notifications\Entrevistas;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EntrevistaProgramada extends Notification
{
    use Queueable;

    public $postulante;
    public $entrevista;

    public function __construct($postulante, $entrevista)
    {
        $this->postulante = $postulante;
        $this->entrevista = $entrevista;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $nombre = trim(($this->postulante->nombres ?? '') . ' ' . ($this->postulante->apellidos ?? ''));
        $fecha = optional($this->entrevista->fecha_entrevista)->format('Y-m-d H:i') ?? 'sin fecha';

        return [
            'titulo' => 'Entrevista programada',
            'mensaje' => "Se programo la entrevista de {$nombre} para {$fecha}.",
            'tipo' => 'entrevistas',
            'nivel' => 'info',
            'icono' => 'calendar-check',
            'url' => route('entrevistas.index'),
            'nombre' => $nombre,
        ];
    }
}
