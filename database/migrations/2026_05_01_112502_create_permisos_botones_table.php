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
        Schema::create('permisos_botones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chofer_id');
            $table->boolean('habilitado')->default(false);
            $table->unsignedBigInteger('admin_id');

            $table->foreign('chofer_id')->references('id')->on('choferes'); 
            $table->foreign('admin_id')->references('id')->on('usuarios'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos_botones');
    }
};
