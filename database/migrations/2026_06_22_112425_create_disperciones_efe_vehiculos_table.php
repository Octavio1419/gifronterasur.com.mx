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
       Schema::create('disperciones_efe_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('efecticard', 50)->index();
             $table->date('fecha')->index(); 
            $table->time('hora');
             $table->decimal('cargado', 10, 2)->default(0.00);       
            $table->decimal('dis_a_cuenta', 10, 2)->default(0.00);  
            $table->decimal('saldo_cuenta', 10, 2)->default(0.00);  

            $table->string('tipo', 10)->nullable();    
            $table->string('usuario', 150)->nullable(); 
            $table->text('concepto')->nullable();      
            $table->string('tipo_combustible', 50)->nullable();
            $table->decimal('precio_combustible', 10, 2)->default(0.00); 

            $table->integer('km_inicial')->default(0);
            $table->integer('km_final')->default(0);
            $table->decimal('litros_cargados', 10, 3)->default(0.000); 
            $table->decimal('rendimiento', 10, 2)->default(0.00);  
                   
            $table->string('estacion_rfc', 20)->nullable()->index(); 
            
           
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disperciones_efe_vehiculos');
    }
};
