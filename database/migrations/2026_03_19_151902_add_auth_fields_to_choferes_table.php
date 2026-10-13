<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void{
        Schema::table('choferes', function (Blueprint $table) {
            // Agregamos usuario y contraseña
            $table->string('usuario_sistema')->unique()->after('apellido_m');
            $table->string('password')->after('usuario_sistema');
            $table->string('foto_perfil')->nullable();
        });
    }

    public function down(): void{
        Schema::table('choferes', function (Blueprint $table) {
            $table->dropColumn(['usuario_sistema', 'password']);
        });
    }
};
