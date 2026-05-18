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
    'name', 'email', 'password',
    'rol', 'plan', 'status',
    'verification_token', 'email_verified_at',
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

public function estaActivo(): bool    { return $this->status === 'activo'; }
public function estaBloqueado(): bool { return $this->status === 'bloqueado'; }
public function estaInactivo(): bool { return $this->status === 'inactivo'; }


public function productos() {
    return $this->hasMany(Producto::class);
}
public function carritoItems() {
    return $this->hasMany(CarritoItem::class);
}
public function pedidos() {
    return $this->hasMany(Pedido::class);
}
public function tarjetas() {
    return $this->hasMany(Tarjeta::class, 'user_id'); // tabla tarjetas_guardadas
}
public function notificaciones() {
    return $this->hasMany(Notificacion::class);
}
public function ventasComoVendedor() {
    return $this->hasMany(PedidoItem::class, 'vendedor_id');
}
}
