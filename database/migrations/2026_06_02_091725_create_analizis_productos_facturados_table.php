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
       Schema::create('analizis_facturado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotizacion_id'); 
            $table->unsignedBigInteger('id_facturada')->nullable(); 
            $table->unsignedBigInteger('usuario_nota_id')->nullable(); 
            $table->string('presupuesto', 50); 
            $table->string('codigo', 50);
            $table->string('nombre', 255);
            $table->decimal('cant_cotizada', 12, 4);
            $table->decimal('cant_facturada', 12, 4)->default(0.0000); 
            $table->decimal('cant_desviacion', 12, 4)->default(0.0000); 
            $table->decimal('cant_pendiente', 12, 4)->default(0.0000); 
            $table->decimal('cant_cancelada', 12, 4)->default(0.0000);  
            // CONTROL DE PRECIOS UNITARIOS
            $table->decimal('precio_cotizado', 12, 2)->default(0.00); 
            $table->decimal('precio_facturado', 12, 2)->default(0.00); 
            $table->decimal('precio_desviacion', 12, 2)->default(0.00); 
            // AUDITORÍA ECONÓMICA DE IMPORTES TOTALES
            $table->decimal('monto_total_cotizado', 12, 2)->default(0.00);
            $table->decimal('monto_total_facturado_acumulado', 12, 2)->default(0.00); 
            $table->decimal('diferencia_monto_total', 12, 2)->default(0.00); 
            // NOTAS / COMPORTAMIENTOS
            $table->text('nota_producto_no_llegara')->nullable(); 
            // DICTAMEN DE AUDITORÍA (Mapeo visual exacto)
            $table->enum('dictamen', [
                'CORRECTO', 
                'PIEZAS DE MAS', 
                'PIEZAS DE MENOS', 
                'NO SURTIDO', 
                'PRECIO ALTERADO'
            ])->default('CORRECTO');
            $table->enum('estado_partida', ['PENDIENTE', 'COMPLETADO', 'EXCESO', 'CANCELADO_MANUAL'])->default('PENDIENTE');
            
            $table->timestamps();

            // INDEXACIÓN Y LLAVES FORÁNEAS
            $table->foreign('cotizacion_id')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->foreign('id_facturada')->references('id')->on('cotizaciones_facturadas')->onDelete('set null');
            $table->foreign('usuario_nota_id')->references('id')->on('usuarios');
            $table->unique(['cotizacion_id', 'codigo']); 
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analizis_productos_facturados');
    }
};
