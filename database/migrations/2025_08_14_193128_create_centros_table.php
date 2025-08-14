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
        Schema::create('centros', function (Blueprint $table) {
            $table->id();
            $table->string('nom_centro', 70);
            $table->string('id_municipio', 50);
            $table->string('barrio_centro', 50)->nullable();
            $table->string('direc_centro', 100)->nullable();
            $table->string('img_centro', 250)->nullable();
            $table->date('fecha_reg_centro')->nullable();
            $table->string('extension', 50)->nullable();
            $table->string('id_regional', 50)->nullable();
            $table->string('departamento', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centros');
    }
};
