<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoImagen;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: obtener ID de categoría por slug
        $cat = fn(string $slug) => Categoria::where('slug', $slug)->value('id');

        $productos = [
            // ── SUBASTAS ──────────────────────────────────────────────────────
            [
                'data' => [
                    'titulo'       => 'Excavadora Caterpillar 320D 2019',
                    'precio'       => 320000,
                    'unidad'       => 'puja actual',
                    'ubicacion'    => 'Mérida, Yuc.',
                    'tipo'         => 'subasta',
                    'categoria_id' => $cat('construccion'),
                    'timer_fin'    => now()->addHours(4),
                ],
                'imagenes' => [
                    ['archivo' => 'excavadora.jpg', 'orden' => 0, 'descripcion' => 'Vista frontal'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Dron Agrícola DJI Agras T40',
                    'precio'       => 85000,
                    'unidad'       => 'puja actual',
                    'ubicacion'    => 'Cancún, Q.R.',
                    'tipo'         => 'subasta',
                    'categoria_id' => $cat('agricultura'),
                    'timer_fin'    => now()->addHour(),
                ],
                'imagenes' => [
                    ['archivo' => 'dronagricola.png', 'orden' => 0, 'descripcion' => 'Vista superior'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Generador Industrial 150 kW Cummins',
                    'precio'       => 55000,
                    'unidad'       => 'puja actual',
                    'ubicacion'    => 'CDMX',
                    'tipo'         => 'subasta',
                    'categoria_id' => $cat('construccion'),
                    'timer_fin'    => now()->addHours(8),
                ],
                'imagenes' => [
                    ['archivo' => 'Generador.jpeg', 'orden' => 0, 'descripcion' => 'Vista general'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Compresor Atlas Copco GA15 2021',
                    'precio'       => 28500,
                    'unidad'       => 'puja actual',
                    'ubicacion'    => 'Monterrey, NL',
                    'tipo'         => 'subasta',
                    'categoria_id' => $cat('construccion'),
                    'timer_fin'    => now()->addHours(2),
                ],
                'imagenes' => [
                    ['archivo' => 'compresor.png', 'orden' => 0, 'descripcion' => 'Vista frontal'],
                ],
            ],

            // ── RENTAS ────────────────────────────────────────────────────────
            [
                'data' => [
                    'titulo'       => 'Andamio Multidireccional 6m – Acero',
                    'precio'       => 450,
                    'unidad'       => '/día',
                    'ubicacion'    => 'Mérida, Yuc.',
                    'tipo'         => 'renta',
                    'categoria_id' => $cat('construccion'),
                ],
                'imagenes' => [
                    ['archivo' => 'andamio.jpg', 'orden' => 0, 'descripcion' => 'Vista completa'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Bomba de Agua Sumergible 3HP',
                    'precio'       => 180,
                    'unidad'       => '/día',
                    'ubicacion'    => 'Valladolid, Yuc.',
                    'tipo'         => 'renta',
                    'categoria_id' => $cat('plomeria'),
                ],
                'imagenes' => [
                    ['archivo' => 'bombagua.png', 'orden' => 0, 'descripcion' => 'Vista general'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Pistola Airless Wagner 2800 PSI',
                    'precio'       => 220,
                    'unidad'       => '/día',
                    'ubicacion'    => 'Progreso, Yuc.',
                    'tipo'         => 'renta',
                    'categoria_id' => $cat('pintura'),
                ],
                'imagenes' => [
                    ['archivo' => 'pistola.jpg', 'orden' => 0, 'descripcion' => 'Vista lateral'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Motosierra Husqvarna 455 Rancher',
                    'precio'       => 350,
                    'unidad'       => '/día',
                    'ubicacion'    => 'Tizimín, Yuc.',
                    'tipo'         => 'renta',
                    'categoria_id' => $cat('jardineria'),
                ],
                'imagenes' => [
                    ['archivo' => 'motosierra.jpg', 'orden' => 0, 'descripcion' => 'Vista completa'],
                ],
            ],

            // ── VENTAS ────────────────────────────────────────────────────────
            [
                'data' => [
                    'titulo'       => 'Taladro Percutor DeWalt 20V – Kit completo',
                    'precio'       => 3200,
                    'ubicacion'    => 'Mérida, Yuc.',
                    'tipo'         => 'venta',
                    'categoria_id' => $cat('construccion'),
                ],
                'imagenes' => [
                    ['archivo' => 'taladro.jpg', 'orden' => 0, 'descripcion' => 'Kit completo'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Soldadora MIG Lincoln Electric 180',
                    'precio'       => 12500,
                    'ubicacion'    => 'Campeche, Camp.',
                    'tipo'         => 'venta',
                    'categoria_id' => $cat('soldadura'),
                ],
                'imagenes' => [
                    ['archivo' => 'soldadora.jpg', 'orden' => 0, 'descripcion' => 'Vista frontal'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Cortadora de Pasto Honda HRX217',
                    'precio'       => 7800,
                    'ubicacion'    => 'Mérida, Yuc.',
                    'tipo'         => 'venta',
                    'categoria_id' => $cat('jardineria'),
                ],
                'imagenes' => [
                    ['archivo' => 'cortador.jpg', 'orden' => 0, 'descripcion' => 'Vista lateral'],
                ],
            ],
            [
                'data' => [
                    'titulo'       => 'Multímetro Digital Fluke 115',
                    'precio'       => 1950,
                    'ubicacion'    => 'Cancún, Q.R.',
                    'tipo'         => 'venta',
                    'categoria_id' => $cat('electricidad'),
                ],
                'imagenes' => [
                    ['archivo' => 'multimetro.jpg', 'orden' => 0, 'descripcion' => 'Vista frontal'],
                ],
            ],
        ];

        foreach ($productos as $item) {
            $producto = Producto::create($item['data']);

            foreach ($item['imagenes'] as $img) {
                $catId = $item['data']['categoria_id'];
                ProductoImagen::create([
                    'producto_id'   => $producto->id,
                    'categoria_id'  => $catId,
                    'ruta'          => 'Imagenes/' . $img['archivo'],
                    'nombre_archivo' => $img['archivo'],
                    'orden'         => $img['orden'],
                    'descripcion'   => $img['descripcion'] ?? null,
                ]);
            }
        }
    }
}