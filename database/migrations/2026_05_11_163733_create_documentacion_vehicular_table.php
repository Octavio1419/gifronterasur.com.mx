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
        Schema::create('documentacion_vehicular', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_original'); 
            $table->string('nombre_archivo');   
            $table->string('ruta_archivo');     
            $table->string('formato')->default('pdf');
            $table->string('peso_archivo')->nullable(); 
            $table->date('fecha_vencimiento')->nullable();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('tipo_documento_id')->nullable(); 
            $table->unsignedBigInteger('subido_por_id')->nullable(); 

            // Relaciones
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos_panel');
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documentos');
            $table->foreign('subido_por_id')->references('id')->on('usuarios');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentacion_vehicular');
    }
};
