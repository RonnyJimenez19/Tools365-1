<?php

namespace App\Http\Controllers;

use App\Mail\RegistroConfirmacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
    if (!Auth::user()->estaVerificado()) {
        Auth::logout();
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Debes verificar tu correo antes de iniciar sesión.']);
    }

    $request->session()->regenerate();   // ← fuera del if, al mismo nivel
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

    $token = Str::random(64);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'rol'      => 'invitado',
    ]);

    // Guardar el token directamente con save() para evitar problemas de mass assignment
    $user->verification_token = $token;
    $user->save();

    try {
        Mail::to($user->email)->send(new RegistroConfirmacion($user));
    } catch (\Throwable $e) {
        //
    }

    return redirect()->route('verificacion.pendiente')
        ->with('email', $user->email);
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

public function verify(Request $request, string $token)
{
    $user = User::where('verification_token', $token)->firstOrFail();

    $user->email_verified_at  = now();
    $user->verification_token = null;
    $user->save();

    return redirect()->route('login')
        ->with('success', '¡Correo verificado! Ya puedes iniciar sesión.');
}

public function reenviarVerificacion(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ], [
        'email.required' => 'El correo es obligatorio.',
        'email.email'    => 'Ingresa un correo válido.',
        'email.exists'   => 'No encontramos una cuenta con ese correo.',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user->estaVerificado()) {
        return back()->with('info', 'Este correo ya fue verificado. Puedes iniciar sesión.');
    }

    // Generar nuevo token
    $user->verification_token = Str::random(64);
    $user->save();

    try {
        Mail::to($user->email)->send(new RegistroConfirmacion($user));
    } catch (\Throwable $e) {
        //
    }

    return back()->with('success', '¡Correo reenviado! Revisa tu bandeja de entrada.');
}

    // ── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        return view('dashboard.index');
    }
}