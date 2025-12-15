<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoRequerimientoCreado extends Notification
{
    use Queueable;

    public $requerimiento;

    public function __construct($requerimiento)
    {
        $this->requerimiento = $requerimiento;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'mensaje' => "Se requiere {$this->requerimiento->cantidad_requerida} {$this->requerimiento->cargo_solicitado} para {$this->requerimiento->cliente}",
            'cantidad' => $this->requerimiento->cantidad_requerida,
            'puesto' => $this->requerimiento->cargo_solicitado,
            'distrito' => $this->requerimiento->cliente,
        ];
    }
}
