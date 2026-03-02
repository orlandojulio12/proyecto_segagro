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
        Schema::create('semovientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_sede_id')->constrained('inventory_sede')->onDelete('cascade');

            // Información general
            $table->string('responsible_department'); // Dependencia responsable
            $table->foreignId('staff_id')->constrained('users'); // Nombre del funcionario
            $table->string('training_center'); // Centro de formación
            $table->string('sede'); // Sede de formación

            // Información calendario
            $table->date('birth_date'); // Fecha de nacimiento
            $table->time('birth_time'); // Hora de nacimiento

            // Detalles
            $table->string('image')->nullable();

            // Características adicionales
            $table->string('birth_area'); // Área de nacimiento
            $table->string('training_environment'); // Ambiente de formación
            $table->string('gender'); // Género
            $table->string('birth_type'); // Tipo de nacimiento (Natural, Cesárea)
            $table->string('animal_type'); // Tipo de semoviente (Vaca, Toro, etc.)
            $table->string('breed')->nullable(); // Raza
            $table->string('weight')->nullable(); // Peso
            $table->string('color')->nullable(); // Color
            $table->string('mother_package')->nullable(); // Paquete de la madre
            $table->decimal('approx_value', 12, 2)->nullable(); // Valor
            $table->string('status'); // Estado (en venta, sacrificado, vivo, muerto)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semovientes');
    }
};
