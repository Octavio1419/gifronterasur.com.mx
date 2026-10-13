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
        Schema::create('tipos_documentos', function (Blueprint $table){
            $table->id();
            $table->string('tipo_documento'); // Póliza de Seguro
            $table->string('slug')->unique(); // poliza-seguro (Para buscarlo fácil en código)
            $table->boolean('requerido')->default(false); // ¿Es obligatorio para que el carro circule?
            $table->integer('dias_alerta')->default(30); // Cuántos días antes avisar del vencimiento
            $table->boolean('estatus')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_documentos');
    }
};
