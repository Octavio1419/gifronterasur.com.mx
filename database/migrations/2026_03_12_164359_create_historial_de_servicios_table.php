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
        Schema::create('historial_de_servicios', function (Blueprint $table) {
            $table->id();  
            $table->date('fecha_servicio');
            $table->integer('km_en_servicio'); // KM que tenía el auto ese día
            $table->date('proxima_fecha')->nullable(); // Fecha sugerida de regreso
            $table->integer('proximo_km')->nullable(); // KM sugerido para siguiente servicio
            
            $table->decimal('costo', 10, 2)->nullable(); // Por si quieren llevar control de gastos
            $table->string('taller_nombre')->nullable(); // Nombre del lugar donde se hizo
            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('servicio_id');
            
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos_panel');
            $table->foreign('servicio_id')->references('id')->on('tipos_servicios_vehicular');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_de_servicios');
    }
};
