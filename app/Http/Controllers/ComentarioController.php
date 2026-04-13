<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    // ── Vista pública con todos los comentarios aprobados ────────────────────

    public function index()
    {
        $comentarios = Comentario::aprobados()
            ->latest()
            ->paginate(9);

        return view('comentarios.index', compact('comentarios'));
    }

    // ── Guardar nuevo comentario (cualquier usuario) ─────────────────────────

    public function store(Request $request)
    {
        $rules = [
            'cuerpo'      => 'required|string|min:10|max:1000',
            'calificacion'=> 'required|integer|min:1|max:5',
        ];

        $messages = [
            'cuerpo.required'      => 'El comentario no puede estar vacío.',
            'cuerpo.min'           => 'El comentario debe tener al menos 10 caracteres.',
            'cuerpo.max'           => 'El comentario no puede superar los 1000 caracteres.',
            'calificacion.required'=> 'Selecciona una calificación.',
            'calificacion.min'     => 'La calificación mínima es 1 estrella.',
            'calificacion.max'     => 'La calificación máxima es 5 estrellas.',
        ];

        // Si no está autenticado pedimos nombre y email
        if (!Auth::check()) {
            $rules['autor_nombre'] = 'required|string|max:100';
            $rules['autor_email']  = 'nullable|email|max:180';

            $messages['autor_nombre.required'] = 'Tu nombre es obligatorio.';
            $messages['autor_email.email']     = 'Ingresa un correo válido.';
        }

        $validated = $request->validate($rules, $messages);

        // Rellenar datos del autor
        if (Auth::check()) {
            $validated['user_id']      = Auth::id();
            $validated['autor_nombre'] = Auth::user()->name;
            $validated['autor_email']  = Auth::user()->email;
        }

        // Estado: aprobado automáticamente si está logueado, pendiente si es invitado
        $validated['estado']    = Auth::check() ? 'aprobado' : 'pendiente';
        $validated['en_inicio'] = false;

        Comentario::create($validated);

        return redirect()->route('comentarios.index')
            ->with('success', Auth::check()
                ? '¡Gracias por tu opinión! Ya está visible.'
                : '¡Gracias! Tu comentario será revisado antes de publicarse.');
    }
}