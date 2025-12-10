<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        $items = [
            ['label' => 'Dashboard', 'href' => route('dashboard'), 'icon' => 'fa-tachometer-alt'],
            ['label' => 'Solicitudes', 'href' => route('requerimientos.filtrar'), 'icon' => 'fa-file-alt'],
            ['label' => 'Postulantes', 'href' => route('postulantes.filtrar'), 'icon' => 'fa-users'],
            [
                'label' => 'Afiches',
                'icon' => 'fa-images',
                'subitems' => [
                    ['label' => 'Crear Afiche', 'href' => route('afiches.index'), 'icon' => 'fa-plus-circle'],
                    ['label' => 'Recursos', 'href' => route('afiches.assets.form'), 'icon' => 'fa-folder-open'],
                ],
            ],
            ['label' => 'Entrevistas', 'href' => route('entrevistas.index'), 'icon' => 'fa-calendar-check'],
        ];

        if ($user && strtoupper($user->rol ?? '') !== 'USUARIO OPERATIVO') {
            $items[] = ['label' => 'Usuarios', 'href' => route('usuarios.index'), 'icon' => 'fa-users-cog'];
        }

        $items[] = ['label' => 'Configuración', 'href' => '#', 'icon' => 'fa-cog'];

        $view->with('sidebarItems', $items);
    }
}
