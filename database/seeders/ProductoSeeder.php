<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // SUBASTAS
            ['titulo' => 'Excavadora Caterpillar 320D 2019',   'precio' => 320000, 'unidad' => 'puja actual', 'ubicacion' => 'Mérida, Yuc.',   'imagen' => 'Imagenes/excavadora.jpg',    'tipo' => 'subasta', 'categoria' => 'construccion', 'timer_fin' => now()->addHours(4)],
            ['titulo' => 'Dron Agrícola DJI Agras T40',         'precio' => 85000,  'unidad' => 'puja actual', 'ubicacion' => 'Cancún, Q.R.',    'imagen' => 'Imagenes/dronagricola.png',  'tipo' => 'subasta', 'categoria' => 'agricultura',   'timer_fin' => now()->addHour()],
            ['titulo' => 'Generador Industrial 150 kW Cummins', 'precio' => 55000,  'unidad' => 'puja actual', 'ubicacion' => 'CDMX',            'imagen' => 'Imagenes/Generador.jpeg',   'tipo' => 'subasta', 'categoria' => 'construccion', 'timer_fin' => now()->addHours(8)],
            ['titulo' => 'Compresor Atlas Copco GA15 2021',     'precio' => 28500,  'unidad' => 'puja actual', 'ubicacion' => 'Monterrey, NL',   'imagen' => 'Imagenes/compresor.png',    'tipo' => 'subasta', 'categoria' => 'construccion', 'timer_fin' => now()->addHours(2)],
            // RENTAS
            ['titulo' => 'Andamio Multidireccional 6m – Acero', 'precio' => 450,   'unidad' => '/día', 'ubicacion' => 'Mérida, Yuc.',    'imagen' => 'Imagenes/andamio.jpg',      'tipo' => 'renta', 'categoria' => 'construccion'],
            ['titulo' => 'Bomba de Agua Sumergible 3HP',         'precio' => 180,   'unidad' => '/día', 'ubicacion' => 'Valladolid, Yuc.', 'imagen' => 'Imagenes/bombagua.png',     'tipo' => 'renta', 'categoria' => 'plomeria'],
            ['titulo' => 'Pistola Airless Wagner 2800 PSI',      'precio' => 220,   'unidad' => '/día', 'ubicacion' => 'Progreso, Yuc.',   'imagen' => 'Imagenes/pistola.jpg',      'tipo' => 'renta', 'categoria' => 'pintura'],
            ['titulo' => 'Motosierra Husqvarna 455 Rancher',     'precio' => 350,   'unidad' => '/día', 'ubicacion' => 'Tizimín, Yuc.',   'imagen' => 'Imagenes/motosierra.jpg',   'tipo' => 'renta', 'categoria' => 'jardineria'],
            // VENTAS
            ['titulo' => 'Taladro Percutor DeWalt 20V – Kit completo', 'precio' => 3200,  'ubicacion' => 'Mérida, Yuc.',    'imagen' => 'Imagenes/taladro.jpg',    'tipo' => 'venta', 'categoria' => 'construccion'],
            ['titulo' => 'Soldadora MIG Lincoln Electric 180',          'precio' => 12500, 'ubicacion' => 'Campeche, Camp.', 'imagen' => 'Imagenes/soldadora.jpg',  'tipo' => 'venta', 'categoria' => 'soldadura'],
            ['titulo' => 'Cortadora de Pasto Honda HRX217',             'precio' => 7800,  'ubicacion' => 'Mérida, Yuc.',    'imagen' => 'Imagenes/cortador.jpg',   'tipo' => 'venta', 'categoria' => 'jardineria'],
            ['titulo' => 'Multímetro Digital Fluke 115',                'precio' => 1950,  'ubicacion' => 'Cancún, Q.R.',    'imagen' => 'Imagenes/multimetro.jpg', 'tipo' => 'venta', 'categoria' => 'electricidad'],
        ];

        foreach ($productos as $p) {
            Producto::create($p);
        }
    }
}