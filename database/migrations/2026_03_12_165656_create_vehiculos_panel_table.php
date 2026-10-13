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
        Schema::create('vehiculos_panel', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('linea');
            $table->string('modelo', 4);
            $table->string('serie')->unique();
            $table->string('placas')->unique();
            $table->string('estado_placa');
            $table->date('ultima_tenencia')->nullable();
            $table->string('efecticard')->nullable();
            $table->string('tarjeta_circulacion')->nullable();
            $table->boolean('estatus_seguro')->default(true);
            $table->string('compania_seguro')->nullable();
            $table->date('fecha_inicio_seguro')->nullable();
            $table->date('fecha_fin_seguro')->nullable();
            $table->integer('km_actual')->default(0);
            $table->string('foto_vehiculo')->nullable();
            $table->unsignedBigInteger('propietario_id');
            $table->unsignedBigInteger('obra_id')->nullable();

            $table->foreign('propietario_id')->references('id')->on('propietarios');
            $table->foreign('obra_id')->references('id')->on('obras');
            
            $table->boolean('estatus')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos_panel');
    }
};
