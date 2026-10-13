<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */  
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'id'=>'1',
            'nombre'=>'Octavio',
            'apellido_p'=>'Nanguyasmu',
            'apellido_m'=>'Perez',
            'correo'=>'octavio.nanguyasmu@gmail.com',
            'nombre_sistema'=>'octavio_teran',
            'password'=>bcrypt('nanguyasmu11'),
            'tipo_rol'=>'1',
            'tipo_sucursal'=>'1',
            'foto_perfil'=>'244',
            'estatus'=>'1',
        ]);
        DB::table('usuarios')->insert([
            'id'=>'2',
            'nombre'=>'Jose ricardo',
            'apellido_p'=>'Burelo',
            'apellido_m'=>'Melgar',
            'correo'=>'jose.ricardo@acerinox.com',
            'nombre_sistema'=>'ricardo',
            'password'=>bcrypt('ricardo2025'),
            'tipo_rol'=>'1',
            'tipo_sucursal'=>'2',
            'foto_perfil'=>'244',
            'estatus'=>'1',
        ]);

          DB::table('usuarios')->insert([
            'id'=>'3',
            'nombre'=>'Marta',
            'apellido_p'=>'Hernandez',
            'apellido_m'=>'Ovilla',
            'correo'=>'admon.vssconstrucciones@gmail.com',
            'nombre_sistema'=>'marta',
            'password'=>bcrypt('marta2025'),
            'tipo_rol'=>'1',
            'tipo_sucursal'=>'1',
            'foto_perfil'=>'244',
            'estatus'=>'1',
        ]);
    }
}
