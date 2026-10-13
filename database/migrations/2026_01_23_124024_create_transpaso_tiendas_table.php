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
        Schema::create('transpaso_tiendas', function (Blueprint $table) {
            $table->id();
            $table->integer('num_transpaso');
            $table->string('sucursal_origen');
            $table->string('sucursal_destino');
            $table->string('producto_clave');
            $table->string('producto_descripcion');
            $table->integer('producto_id');
            $table->string('proovedor');   
            $table->date('fecha_transpaso');
            $table->boolean('vigente')->default(true);
            $table->string('tipo_movimiento');
            $table->integer('cantidad_transpaso');
            $table->integer('costo_total');
            $table->string('linea');
            $table->string('color');
            $table->unsignedBigInteger('sucursal_id');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transpaso_tiendas');
    }
};
