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
        Schema::create('cotizaciones_facturas_cierre', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('id_factura')->nullabe();
            $table->unsignedBigInteger('id_usuario')->nullabe();
            
            // Montos financieros usando decimal para no perder centavos
            $table->decimal('total_cotizado', 12, 2)->comment('Monto final authorized en la cotización base');
            $table->decimal('total_facturado', 12, 2)->comment('Suma física acumulada de todos los XML vinculados');
            $table->decimal('monto_diferencia', 12, 2)->default(0.00)->comment('Resultado de: total_facturado - total_cotizado');
            
            // Flags de control de auditoría
            $table->boolean('cuadro_perfecto')->index()->comment('1 = Sí cuadró (Regla 1), 0 = Cerrado con desfase (Regla 2)');
            $table->unsignedInteger('cantidad_facturas_ligadas')->comment('Número total de XMLs procesados al momento del cierre');
            
            // Justificación en caso de que existan diferencias de dinero
            $table->text('nota_auditoria')->nullable()->comment('Justificación obligatoria si cuadro_perfecto es 0 (ej. producto no llegará)');
           
            $table->timestamp('fecha_cierre')->useCurrent();

            $table->foreign('id_factura')->references('id')->on('cotizaciones');
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_facturas_cierre');
    }
};
