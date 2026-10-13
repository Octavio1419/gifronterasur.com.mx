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
        Schema::create('cotizaciones_conciliadas', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_presupuesto')->index(); 
            $table->string('folio_conciliacion', 50)->unique();
            $table->decimal('monto_presupuesto', 12, 2);
            $table->decimal('monto_factura', 12, 2);
            $table->decimal('diferencia', 12, 2);
            $table->dateTime('fecha_presupuesto');
            $table->dateTime('fecha_conciliado');
            $table->text('comentarios')->nullable();
            $table->timestamps();

            $table->foreign('id_presupuesto')->references('id')->on('cotizaciones');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_conciliadas');
    }
};
