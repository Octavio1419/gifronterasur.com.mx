<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->string('apellido_p', 250);
            $table->string('apellido_m', 250);
            $table->string('correo')->unique();
            $table->string('nombre_sistema', 100);
            $table->string('password'); 
            $table->unsignedBigInteger('tipo_rol');
            $table->unsignedBigInteger('tipo_sucursal');
            $table->string('foto_perfil', 500)->nullable(); 
            $table->timestamps(); 
            $table->boolean('estatus'); 
            
            $table->foreign('tipo_rol')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('tipo_sucursal')->references('id')->on('sucursales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
