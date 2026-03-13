<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    protected $table = 'producto_imagenes';

    protected $fillable = [
        'producto_id', 'categoria_id',
        'ruta', 'nombre_archivo',
        'orden', 'descripcion', 'estado',
    ];

    // Pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Pertenece a una categoría (clasificación de la imagen)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}