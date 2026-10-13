<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. VIRGILIO CHAC CANUL 26/04/1994
     */
    public function up(): void
    {
        Schema::create('detalle_conciliacion_facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cotizacion_conciliada')->index();
            $table->unsignedBigInteger('id_cotizacion_facturada');
            $table->decimal('monto_individual_factura', 12, 2);
            $table->timestamps();
            //la relacion
            $table->foreign('id_cotizacion_conciliada')->references('id')->on('cotizaciones_conciliadas');
            $table->foreign('id_cotizacion_facturada')->references('id')->on('cotizaciones_facturadas');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('detalle_conciliacion_facturas');
    }
};
