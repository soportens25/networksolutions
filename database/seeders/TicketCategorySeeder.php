<?php
// database/seeders/TicketCategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Soporte Técnico',
                'description' => 'Problemas técnicos generales',
                'color' => '#3B82F6'
            ],
            [
                'name' => 'Hardware',
                'description' => 'Problemas de hardware y equipos',
                'color' => '#EF4444'
            ],
            [
                'name' => 'Software',
                'description' => 'Problemas de aplicaciones y software',
                'color' => '#10B981'
            ],
            [
                'name' => 'Red y Conectividad',
                'description' => 'Problemas de red y conexión',
                'color' => '#F59E0B'
            ],
            [
                'name' => 'Cuentas de Usuario',
                'description' => 'Problemas de acceso y permisos',
                'color' => '#8B5CF6'
            ],
        ];

        foreach ($categories as $category) {
            TicketCategory::create($category);
        }
    }
}
