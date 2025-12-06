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
        Schema::create('sedes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_sede', 50)->nullable();
            $table->foreignId('centro_id')->constrained('centros');
            $table->string('matricula_inmobiliario', 50)->nullable();
            $table->string('barrio_sede', 50)->nullable();
            $table->string('direc_sede', 50)->nullable();
            $table->string('localidad', 50)->nullable();
            $table->text('img_sede')->nullable();
            $table->datetime('fecha_reg_sede')->nullable();
            $table->longText('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sedes');
    }
};
