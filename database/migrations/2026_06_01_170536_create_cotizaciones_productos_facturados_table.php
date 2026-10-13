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
        Schema::create('cotizaciones_productos_facturados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50); // Añadido para poder hacer el cruce exacto con la cotización original
            $table->string('nombre', 255);
            $table->string('um', 10); // Unidad de Medida (PZ, MTR, etc)
            $table->decimal('cant_facturada', 12, 4);
            $table->decimal('precio_unitario', 12, 4);
            $table->decimal('importe', 12, 2); // cant_facturada * precio_unitario
            $table->decimal('descuento', 12, 2)->default(0.00);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0.00);
            $table->decimal('impor_con_des', 12, 2);
            $table->decimal('iva', 12, 2)->default(0.00);
            $table->timestamps();

            $table->unsignedBigInteger('id_facturada');

            //relación con cotizaciones_facturadas
            $table->foreign('id_facturada')->references('id')->on('cotizaciones_facturadas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_productos_facturados');
    }
};
