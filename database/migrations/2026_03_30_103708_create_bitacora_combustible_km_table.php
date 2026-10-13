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
        Schema::create('bitacora_combustible_km', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('chofer_id');
            $table->integer('km_lectura'); // El número que el chofer escribe
            $table->string('foto_odometro'); // La evidencia que capturamos con el CROP
            $table->enum('tipo_registro', ['inicio', 'fin']); // Para saber si es al empezar o terminar jornada
            $table->text('notas_estado')->nullable(); // "Rayón en puerta", "Falta tapón", etc.
            
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos_panel');
            $table->foreign('chofer_id')->references('id')->on('choferes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_combustible_km');
    }
};
