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
        Schema::create('choferes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_p');
            $table->string('apellido_m');
            $table->string('telefono');
            $table->string('n_licencia')->unique();
            $table->date('vigencia_licencia'); 
            $table->string('tipo_sangre', 5)->nullable();
            $table->string('contacto_emergencia')->nullable();
            $table->string('telefono_emergencia', 15)->nullable();
            $table->string('foto_chofer')->nullable();
            $table->string('foto_licencia_enfre')->nullable();
            $table->string('foto_licencia_reverso')->nullable();
            $table->string('foto_ine')->nullable();
            $table->unsignedBigInteger('vehiculo_id')->unique();

            
            // Relacion 1:1 con Vehiculo
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos_panel');
            
            $table->boolean('estatus')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choferes');
    }
};
