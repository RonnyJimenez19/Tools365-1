<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['slug' => 'construccion', 'nombre' => 'Construcción',  'icono' => 'bi-building',         'color' => 'primary'],
            ['slug' => 'agricultura',  'nombre' => 'Agricultura',   'icono' => 'bi-tree',              'color' => 'success'],
            ['slug' => 'ganaderia',    'nombre' => 'Ganadería',     'icono' => 'bi-egg',               'color' => 'warning'],
            ['slug' => 'alimentos',    'nombre' => 'Alimentos',     'icono' => 'bi-cup-straw',         'color' => 'danger'],
            ['slug' => 'plomeria',     'nombre' => 'Plomería',      'icono' => 'bi-droplet',           'color' => 'info'],
            ['slug' => 'electricidad', 'nombre' => 'Electricidad',  'icono' => 'bi-lightning-charge',  'color' => 'dark'],
            ['slug' => 'carpinteria',  'nombre' => 'Carpintería',   'icono' => 'bi-hammer',            'color' => 'primary'],
            ['slug' => 'jardineria',   'nombre' => 'Jardinería',    'icono' => 'bi-flower1',           'color' => 'success'],
            ['slug' => 'soldadura',    'nombre' => 'Soldadura',     'icono' => 'bi-fire',              'color' => 'warning'],
            ['slug' => 'pintura',      'nombre' => 'Pintura',       'icono' => 'bi-paint-bucket',      'color' => 'danger'],
            ['slug' => 'transporte',   'nombre' => 'Transporte',    'icono' => 'bi-truck',             'color' => 'info'],
            ['slug' => 'otros',        'nombre' => 'Otros',         'icono' => 'bi-gear',              'color' => 'secondary'],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}