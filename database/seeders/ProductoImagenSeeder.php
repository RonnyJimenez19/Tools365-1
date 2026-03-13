<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\ProductoImagen;

class ProductoImagenSeeder extends Seeder
{
    public function run(): void
    {
        // Mapa: título del producto => segunda imagen
        $segundasImagenes = [
            'Excavadora Caterpillar 320D 2019'   => ['archivo' => 'excavadora2.jpg',   'descripcion' => 'Vista lateral'],
            'Dron Agrícola DJI Agras T40'         => ['archivo' => 'dronagricola2.jpeg','descripcion' => 'Vista inferior'],
            'Generador Industrial 150 kW Cummins' => ['archivo' => 'Generador2.jpeg',   'descripcion' => 'Panel de control'],
            'Compresor Atlas Copco GA15 2021'      => ['archivo' => 'Compresor2.jpg',    'descripcion' => 'Vista posterior'],
        ];

        foreach ($segundasImagenes as $titulo => $img) {
            $producto = Producto::where('titulo', $titulo)->first();

            if (!$producto) {
                $this->command->warn("Producto no encontrado: {$titulo}");
                continue;
            }

            // Evitar duplicados si el seeder se corre más de una vez
            $yaExiste = ProductoImagen::where('producto_id', $producto->id)
                ->where('nombre_archivo', $img['archivo'])
                ->exists();

            if ($yaExiste) {
                $this->command->info("Ya existe imagen '{$img['archivo']}' para: {$titulo}");
                continue;
            }

            ProductoImagen::create([
                'producto_id'    => $producto->id,
                'categoria_id'   => $producto->categoria_id,
                'ruta'           => 'Imagenes/' . $img['archivo'],
                'nombre_archivo' => $img['archivo'],
                'orden'          => 1,   // orden 1 = segunda imagen (0 ya existe)
                'descripcion'    => $img['descripcion'],
            ]);

            $this->command->info("Imagen agregada a: {$titulo}");
        }
    }
}