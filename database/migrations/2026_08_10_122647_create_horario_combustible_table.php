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
        Schema::create('horario_combustible', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('dia_semana'); // 0=Lunes,1=Martes,2=Miércoles,3=Jueves,4=Viernes,5=Sábado,6=Domingo
            $table->time('horario_inicio');
            $table->time('horario_finalizar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_combustible');
    }
};
