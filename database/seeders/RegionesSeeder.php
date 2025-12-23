<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regiones')->insert([
            ['id' => 1, 'region' => 'Arica y Parinacota', 'abreviatura' => 'AP', 'capital' => 'Arica'],
            ['id' => 2, 'region' => 'Tarapacá', 'abreviatura' => 'TA', 'capital' => 'Iquique'],
            ['id' => 3, 'region' => 'Antofagasta', 'abreviatura' => 'AN', 'capital' => 'Antofagasta'],
            ['id' => 4, 'region' => 'Atacama', 'abreviatura' => 'AT', 'capital' => 'Copiapó'],
            ['id' => 5, 'region' => 'Coquimbo', 'abreviatura' => 'CO', 'capital' => 'La Serena'],
            ['id' => 6, 'region' => 'Valparaiso', 'abreviatura' => 'VA', 'capital' => 'valparaíso'],
            ['id' => 7, 'region' => 'Metropolitana de Santiago', 'abreviatura' => 'RM', 'capital' => 'Santiago'],
            ['id' => 8, 'region' => 'Libertador General Bernardo O\'Higgins', 'abreviatura' => 'OH', 'capital' => 'Rancagua'],
            ['id' => 9, 'region' => 'Maule', 'abreviatura' => 'MA', 'capital' => 'Talca'],
            ['id' => 10, 'region' => 'Ñuble', 'abreviatura' => 'NB', 'capital' => 'Chillán'],
            ['id' => 11, 'region' => 'Biobío', 'abreviatura' => 'BI', 'capital' => 'Concepción'],
            ['id' => 12, 'region' => 'La Araucanía', 'abreviatura' => 'IAR', 'capital' => 'Temuco'],
            ['id' => 13, 'region' => 'Los Ríos', 'abreviatura' => 'LR', 'capital' => 'Valdivia'],
            ['id' => 14, 'region' => 'Los Lagos', 'abreviatura' => 'LL', 'capital' => 'Puerto Montt'],
            ['id' => 15, 'region' => 'Aysén del General Carlos Ibáñez del Campo', 'abreviatura' => 'AI', 'capital' => 'Coyhaique'],
            ['id' => 16, 'region' => 'Magallanes y de la Antártica Chilena', 'abreviatura' => 'MG', 'capital' => 'Punta Arenas'],
        ]);
    }
}
