<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /dashboard/notificaciones
     * Lista paginada de notificaciones del usuario autenticado.
     */
    public function index()
    {
        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        // Marcar todas como leídas al abrir la página
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true, 'leida_at' => now()]);

        return view('dashboard.notificaciones', compact('notificaciones'));
    }

    /**
     * POST /dashboard/notificaciones/{notificacion}/leer
     * Marcar una sola notificación como leída (AJAX).
     */
    public function marcarLeida(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== Auth::id()) abort(403);
        $notificacion->marcarLeida();

        return response()->json(['ok' => true]);
    }

    /**
     * POST /dashboard/notificaciones/leer-todas
     */
    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true, 'leida_at' => now()]);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    /**
     * GET /dashboard/notificaciones/conteo  (AJAX — para el badge del topbar)
     */
    public function conteo()
    {
        $conteo = Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->count();

        return response()->json(['conteo' => $conteo]);
    }
}