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
       Schema::create('incidencias_vehiculares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('chofer_id');
            $table->string('titulo_reporte'); 
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica']);
            $table->string('foto_evidencia')->nullable();
            $table->enum('estatus_reporte', ['abierto', 'en_revision', 'reparado', 'rechazado'])->default('abierto');
            
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
        Schema::dropIfExists('incidencias_vehiculares');
    }
};
