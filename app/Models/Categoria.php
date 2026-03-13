<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'slug', 'nombre', 'icono', 'color', 'estado',
    ];

    // Una categoría tiene muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Una categoría tiene muchas imágenes asociadas
    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class);
    }
}