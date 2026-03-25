<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    /**
     * Carga los links iniciales del navbar.
     * Ejecutar con: php artisan db:seed --class=NavItemSeeder
     *
     * El campo `orden` define el orden de aparición (menor = más a la izquierda).
     * Modificar desde la BD (o un futuro panel admin) sin tocar código Blade.
     */
    public function run(): void
    {
        $items = [
            [
                'label'     => 'Inicio',
                'url'       => '/#inicio',
                'icono'     => 'bi-house-fill',
                'orden'     => 10,
                'es_acento' => false,
                'target'    => null,
            ],
            [
                'label'     => 'Ofertas',
                'url'       => '#',
                'icono'     => 'bi-tags-fill',
                'orden'     => 20,
                'es_acento' => false,
                'target'    => null,
            ],
            [
                'label'     => 'Planes',
                'url'       => '/#planes',
                'icono'     => 'bi-star-fill',
                'orden'     => 30,
                'es_acento' => false,
                'target'    => null,
            ],
            [
                'label'     => 'Contacto',
                'url'       => '/#contacto',
                'icono'     => 'bi-chat-dots-fill',
                'orden'     => 40,
                'es_acento' => false,
                'target'    => null,
            ],
            [
                'label'     => 'Publicar herramienta',
                'url'       => '#',
                'icono'     => 'bi-plus-circle-fill',
                'orden'     => 50,
                'es_acento' => true,   // aparece con color acento al extremo derecho
                'target'    => null,
            ],
        ];

        foreach ($items as $item) {
            NavItem::firstOrCreate(['label' => $item['label']], $item);
        }
    }
}