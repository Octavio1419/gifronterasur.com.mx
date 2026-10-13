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
        /*Schema::create('solicitudes_combustible', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
        Schema::create('solicitudes_combustible', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('chofer_id');
            $table->unsignedBigInteger('obra_id')->nullable(); // Para saber a qué costo de obra va
            $table->unsignedBigInteger('usuario_id')->nullable(); // Para saber quién autorizó o rechazó, si es que ya se procesó
            
            // Datos de la solicitud
            $table->decimal('monto_solicitado', 10, 2); 
            $table->integer('km_actual'); // El chofer confirma el KM al pedir gasolina
            $table->enum('tipo_combustible', ['Diesel', 'Magna', 'Premium'])->default('Diesel');
            
            // Gestión de la solicitud
            $table->enum('estatus', ['pendiente', 'autorizado', 'rechazado', 'cargado'])->default('pendiente');
            $table->string('folio_autorizacion')->nullable(); 
            
            // Evidencia (Después de que cargue, puede subir el ticket)
            $table->string('foto_ticket')->nullable();
            $table->decimal('monto_real_cargado', 10, 2)->nullable();
            
            $table->text('notas_chofer')->nullable();
            $table->text('notas_admin')->nullable();

            // Llaves foráneas
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos_panel');
            $table->foreign('chofer_id')->references('id')->on('choferes');
            $table->foreign('obra_id')->references('id')->on('obras');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_combustible');
    }
};
