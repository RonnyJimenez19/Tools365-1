<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'verification_token',
        'email_verified_at',
    ];

    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Helper para usar en Blade y controladores
    public function esAdmin(): bool    { return $this->rol === 'admin'; }
    public function esGerente(): bool  { return $this->rol === 'gerente'; }
    public function esInvitado(): bool { return $this->rol === 'invitado'; }

    // Puede ver contenido pero no modificar
    public function puedeEditar(): bool { return in_array($this->rol, ['admin', 'gerente']); }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


public function estaVerificado(): bool
{
    return !is_null($this->email_verified_at);
}

}
