<?php

namespace Database\Seeders;

use App\Models\Comentario;
use Illuminate\Database\Seeder;

class ComentarioSeeder extends Seeder
{
    public function run(): void
    {
        $comentarios = [
            [
                'autor_nombre' => 'Carlos Mendoza',
                'cuerpo'       => 'Excelente plataforma, encontré la excavadora que necesitaba en menos de un día. El proceso de renta fue muy sencillo y el vendedor muy profesional.',
                'calificacion' => 5,
                'estado'       => 'aprobado',
                'en_inicio'    => true,
            ],
            [
                'autor_nombre' => 'Laura García',
                'cuerpo'       => 'Muy buena experiencia vendiendo mi maquinaria de construcción. En dos semanas ya tenía comprador. La plataforma es intuitiva y segura.',
                'calificacion' => 5,
                'estado'       => 'aprobado',
                'en_inicio'    => true,
            ],
            [
                'autor_nombre' => 'Roberto Sánchez',
                'cuerpo'       => 'Participé en una subasta y gané una retroexcavadora a muy buen precio. El proceso fue transparente y sin contratiempos.',
                'calificacion' => 4,
                'estado'       => 'aprobado',
                'en_inicio'    => true,
            ],
            [
                'autor_nombre' => 'Ana Torres',
                'cuerpo'       => 'Muy contenta con el soporte al cliente. Tuve una duda sobre el proceso de renta y me respondieron en menos de 30 minutos.',
                'calificacion' => 5,
                'estado'       => 'aprobado',
                'en_inicio'    => false,
            ],
            [
                'autor_nombre' => 'Miguel Reyes',
                'cuerpo'       => 'Buena plataforma pero me gustaría que tuvieran más categorías para equipo de cocina industrial. De resto todo muy bien.',
                'calificacion' => 4,
                'estado'       => 'aprobado',
                'en_inicio'    => false,
            ],
        ];

        foreach ($comentarios as $data) {
            Comentario::create($data);
        }
    }
}