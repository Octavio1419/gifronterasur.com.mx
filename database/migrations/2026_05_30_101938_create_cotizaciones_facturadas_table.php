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
        Schema::create('cotizaciones_facturadas', function (Blueprint $table) {
            $table->id();
            $table->string('presupuesto', 50); 
            $table->string('rfc', 15);
            $table->string('nombre', 255); 
            $table->dateTime('fecha_timbrado');
            $table->string('uuid', 36)->unique(); 
            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2);
            $table->integer('emision_completa')->default(0); // 0 = Incompleto (faltan productos), 1 = Completado
            $table->unsignedBigInteger('usuario_analizador'); // ID del usuario que procesó la carga
            $table->timestamps();

            $table->index('presupuesto'); // Index para búsquedas rápidas por folio

            $table->foreign('usuario_analizador')->references('id')->on('usuarios');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_facturadas');
    }
};
