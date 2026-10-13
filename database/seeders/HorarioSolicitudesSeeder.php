<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HorarioSolicitudesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        DB::table('horario_combustible')->insert([
            'dia_semana' => 3,
            'horario_inicio' => '08:00:00',
            'horario_finalizar' => '15:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
