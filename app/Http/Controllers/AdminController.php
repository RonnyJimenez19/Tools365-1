<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── Listado de usuarios ──────────────────────────────────────────────────
    public function usuarios(Request $request)
    {
        $query = User::query();

        // Búsqueda por nombre o email
        if ($search = $request->input('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($rol = $request->input('rol')) {
            $query->where('rol', $rol);
        }

        // Filtro por plan
        if ($plan = $request->input('plan')) {
            $query->where('plan', $plan);
        }

        // Filtro por estado
if ($estado = $request->input('estado')) {
    $query->where('status', $estado);
}

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('dashboard.admin.usuarios', compact('usuarios'));
    }

    // ── Actualizar rol, plan o bloqueo (modal) ───────────────────────────────
    public function updateUsuario(Request $request, User $user)
    {
        // Un admin no puede modificarse a sí mismo de ciertas formas
        if ($user->id === auth()->id() && $request->filled('rol') && $request->input('rol') !== 'admin') {
            return back()->with('error', 'No puedes quitarte el rol de administrador.');
        }

        $validated = $request->validate([
            'rol'       => 'sometimes|in:admin,gerente,invitado',
            'plan'      => 'sometimes|in:basico,pro,enterprise',
            'status' => 'sometimes|in:activo,bloqueado',

        ]);

        $user->update($validated);

        return back()->with('success', "Usuario {$user->name} actualizado correctamente.");
    }

    // ── Toggle bloqueo rápido (botón directo) ────────────────────────────────
public function toggleBloqueo(User $user)
{
    if ($user->id === auth()->id()) {
        return back()->with('error', 'No puedes bloquearte a ti mismo.');
    }

    $nuevoStatus = $user->status === 'bloqueado' ? 'activo' : 'bloqueado';
    $user->update(['status' => $nuevoStatus]);

    return back()->with('success', "Usuario {$user->name} {$nuevoStatus} correctamente.");
}
}