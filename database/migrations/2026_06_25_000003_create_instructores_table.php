<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('documento', 20)->unique();
            $table->string('email', 150)->unique()->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('especialidad', 200)->nullable();
            $table->enum('tipo_contrato', ['planta', 'contrato', 'hora_catedra'])->default('contrato');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructores');
    }
};
