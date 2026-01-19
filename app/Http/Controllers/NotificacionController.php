<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $estado = $request->query('estado', 'todas');

        $query = match ($estado) {
            'no_leidas' => $user->unreadNotifications(),
            'leidas'    => $user->readNotifications(),
            default     => $user->notifications(),
        };

        // ✅ NO uses latest() aquí porque el query ya viene ordenado por created_at desc
        // ✅ Si quieres un orden más estable, agrega otra columna DISTINTA (id)
        $notificacionesListado = $query
            ->orderByDesc('id')   // opcional pero recomendado (no repite created_at)
            ->paginate(15)
            ->withQueryString();

        $notificaciones = $user->unreadNotifications()->take(5)->get();
        $totalNotificaciones = $user->notifications()->count();
        $noLeidas = $user->unreadNotifications()->count();
        $leidas = $user->readNotifications()->count();

        return view('notificaciones.index', compact(
            'notificacionesListado',
            'notificaciones',
            'totalNotificaciones',
            'noLeidas',
            'leidas',
            'estado'
        ));
    }

    public function markAsRead(Request $request, string $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->whereKey($id)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back();
    }
}
