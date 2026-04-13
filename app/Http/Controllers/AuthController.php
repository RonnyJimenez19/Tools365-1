<?php

namespace App\Http\Controllers;

use App\Mail\RegistroConfirmacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ── Login público (usuarios de la plataforma) ────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // Inicializar marca de actividad para el middleware de timeout
            session(['last_activity_at' => now()]);

            return redirect()->intended(route('inicio'))
                ->with('success', 'Sesión iniciada correctamente.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Las credenciales no son correctas.']);
    }

    // ── Login admin (solo personal interno → dashboard) ──────────────────────

    public function showAdminLogin()
    {
        if (Auth::check() && Auth::user()->puedeEditar()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login-admin');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->puedeEditar()) {
                Auth::logout();
                $request->session()->invalidate();
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'No tienes acceso al panel de administración.']);
            }

            $request->session()->regenerate();
            session(['last_activity_at' => now()]);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenido al panel, ' . auth()->user()->name . '.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Las credenciales no son correctas.']);
    }

    // ── Registro público ─────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo es obligatorio.',
            'email.email'        => 'Ingresa un correo válido.',
            'email.unique'       => 'Este correo ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'invitado',
        ]);

        // ── Enviar correo de confirmación ────────────────────────────────────
        // Usamos try/catch para que un fallo de SMTP no interrumpa el registro
        try {
            Mail::to($user->email)->send(new RegistroConfirmacion($user));
        } catch (\Throwable $e) {
            // En producción loguear: \Log::error('Mail error: ' . $e->getMessage());
        }

        Auth::login($user);
        session(['last_activity_at' => now()]);

        return redirect()->route('inicio')
            ->with('success', '¡Bienvenido a Tools365, ' . $user->name . '! Revisa tu correo para confirmar tu registro.');
    }

    // ── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        $wasAdmin = Auth::check() && Auth::user()->puedeEditar();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $wasAdmin
            ? redirect()->route('admin.login')->with('success', 'Sesión cerrada correctamente.')
            : redirect()->route('inicio')->with('success', 'Sesión cerrada correctamente.');
    }

    // ── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        return view('dashboard.index');
    }
}