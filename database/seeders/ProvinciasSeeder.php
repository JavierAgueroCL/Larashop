<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provincias')->insert([
            ['id' => 1, 'provincia' => 'Arica', 'region_id' => 1],
            ['id' => 2, 'provincia' => 'Parinacota', 'region_id' => 1],
            ['id' => 3, 'provincia' => 'Iquique', 'region_id' => 2],
            ['id' => 4, 'provincia' => 'El Tamarugal', 'region_id' => 2],
            ['id' => 5, 'provincia' => 'Tocopilla', 'region_id' => 3],
            ['id' => 6, 'provincia' => 'El Loa', 'region_id' => 3],
            ['id' => 7, 'provincia' => 'Antofagasta', 'region_id' => 3],
            ['id' => 8, 'provincia' => 'Chañaral', 'region_id' => 4],
            ['id' => 9, 'provincia' => 'Copiapó', 'region_id' => 4],
            ['id' => 10, 'provincia' => 'Huasco', 'region_id' => 4],
            ['id' => 11, 'provincia' => 'Elqui', 'region_id' => 5],
            ['id' => 12, 'provincia' => 'Limarí', 'region_id' => 5],
            ['id' => 13, 'provincia' => 'Choapa', 'region_id' => 5],
            ['id' => 14, 'provincia' => 'Petorca', 'region_id' => 6],
            ['id' => 15, 'provincia' => 'Los Andes', 'region_id' => 6],
            ['id' => 16, 'provincia' => 'San Felipe de Aconcagua', 'region_id' => 6],
            ['id' => 17, 'provincia' => 'Quillota', 'region_id' => 6],
            ['id' => 18, 'provincia' => 'Valparaiso', 'region_id' => 6],
            ['id' => 19, 'provincia' => 'San Antonio', 'region_id' => 6],
            ['id' => 20, 'provincia' => 'Isla de Pascua', 'region_id' => 6],
            ['id' => 21, 'provincia' => 'Marga Marga', 'region_id' => 6],
            ['id' => 22, 'provincia' => 'Chacabuco', 'region_id' => 7],
            ['id' => 23, 'provincia' => 'Santiago', 'region_id' => 7],
            ['id' => 24, 'provincia' => 'Cordillera', 'region_id' => 7],
            ['id' => 25, 'provincia' => 'Maipo', 'region_id' => 7],
            ['id' => 26, 'provincia' => 'Melipilla', 'region_id' => 7],
            ['id' => 27, 'provincia' => 'Talagante', 'region_id' => 7],
            ['id' => 28, 'provincia' => 'Cachapoal', 'region_id' => 8],
            ['id' => 29, 'provincia' => 'Colchagua', 'region_id' => 8],
            ['id' => 30, 'provincia' => 'Cardenal Caro', 'region_id' => 8],
            ['id' => 31, 'provincia' => 'Curicó', 'region_id' => 9],
            ['id' => 32, 'provincia' => 'Talca', 'region_id' => 9],
            ['id' => 33, 'provincia' => 'Linares', 'region_id' => 9],
            ['id' => 34, 'provincia' => 'Cauquenes', 'region_id' => 9],
            ['id' => 35, 'provincia' => 'Diguillín', 'region_id' => 10],
            ['id' => 36, 'provincia' => 'Itata', 'region_id' => 10],
            ['id' => 37, 'provincia' => 'Punilla', 'region_id' => 10],
            ['id' => 38, 'provincia' => 'Bio Bío', 'region_id' => 11],
            ['id' => 39, 'provincia' => 'Concepción', 'region_id' => 11],
            ['id' => 40, 'provincia' => 'Arauco', 'region_id' => 11],
            ['id' => 41, 'provincia' => 'Malleco', 'region_id' => 12],
            ['id' => 42, 'provincia' => 'Cautín', 'region_id' => 12],
            ['id' => 43, 'provincia' => 'Valdivia', 'region_id' => 13],
            ['id' => 44, 'provincia' => 'Ranco', 'region_id' => 13],
            ['id' => 45, 'provincia' => 'Osorno', 'region_id' => 14],
            ['id' => 46, 'provincia' => 'Llanquihue', 'region_id' => 14],
            ['id' => 47, 'provincia' => 'Chiloé', 'region_id' => 14],
            ['id' => 48, 'provincia' => 'Palena', 'region_id' => 14],
            ['id' => 49, 'provincia' => 'Coyhaique', 'region_id' => 15],
            ['id' => 50, 'provincia' => 'Aysén', 'region_id' => 15],
            ['id' => 51, 'provincia' => 'General Carrera', 'region_id' => 15],
            ['id' => 52, 'provincia' => 'Capitán Prat', 'region_id' => 15],
            ['id' => 53, 'provincia' => 'Última Esperanza', 'region_id' => 16],
            ['id' => 54, 'provincia' => 'Magallanes', 'region_id' => 16],
            ['id' => 55, 'provincia' => 'Tierra del Fuego', 'region_id' => 16],
            ['id' => 56, 'provincia' => 'Antártica Chilena', 'region_id' => 16],
        ]);
    }
}
