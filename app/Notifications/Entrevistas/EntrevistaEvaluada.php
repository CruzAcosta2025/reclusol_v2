<?php

namespace App\Notifications\Entrevistas;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EntrevistaEvaluada extends Notification
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
        $resultado = strtoupper((string)($this->entrevista->resultado ?? 'EVALUADO'));

        return [
            'titulo' => 'Entrevista evaluada',
            'mensaje' => "Se registro la evaluacion de {$nombre}. Resultado: {$resultado}.",
            'tipo' => 'entrevistas',
            'nivel' => 'alerta',
            'icono' => 'clipboard-check',
            'url' => route('entrevistas.index'),
            'nombre' => $nombre,
        ];
    }
}
