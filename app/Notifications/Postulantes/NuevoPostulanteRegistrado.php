<?php

namespace App\Notifications\Postulantes;

use Illuminate\Bus\Queueable;
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
        $nombreCompleto = trim(($this->postulante->nombres ?? '') . ' ' . ($this->postulante->apellidos ?? ''));
        $dni = $this->postulante->dni ?? '-';

        return [
            'titulo'   => 'Postulante registrado',
            'mensaje'  => "Se registró un nuevo postulante: {$nombreCompleto} (DNI: {$dni}).",
            'tipo'     => 'postulantes',
            'nivel'    => 'info',
            'icono'    => 'user-plus',

            // Referencia para redirección
            //'ref_id'   => $this->postulante->id ?? null,
            //'url'      => ($this->postulante->id ?? null)
              //  ? route('postulantes.show', $this->postulante->id)
                //: null,

            // Extras opcionales
            //'dni'      => $dni,
            //'nombre'   => $this->postulante->nombres ?? '',
            //'apellidos' => $this->postulante->apellidos ?? '',
        ];
    }
}
