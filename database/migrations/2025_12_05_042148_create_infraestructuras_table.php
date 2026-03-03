<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfraestructurasTable extends Migration
{
    public function up()
    {
        Schema::create('infraestructuras', function (Blueprint $table) {
            $table->id();

            // Información general
            $table->foreignId('dependencia_id')->nullable()->constrained('dependencias'); // opcional, puedes mantenerlo
            // Información general
            $table->foreignId('unidad_id')->constrained('dependency_units', 'dependency_unit_id');
            $table->foreignId('subunidad_id')->constrained('dependency_subunits', 'subunit_id');
            $table->foreignId('user_id')->constrained('users'); // funcionario
            $table->foreignId('centro_id')->constrained('centros');
            $table->foreignId('sede_id')->constrained('sedes');
            $table->string('ambiente')->nullable();

            // Información calendario
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            // Detalles de la necesidad
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();

            // Características adicionales
            $table->enum('nivel_riesgo', ['bajo', 'medio', 'alto']);
            $table->enum('nivel_prioridad', ['baja', 'media', 'alta'])->nullable();
            $table->string('tipo_necesidad');
            $table->string('area_necesidad')->nullable();
            $table->enum('nivel_complejidad', ['baja', 'media', 'alta'])->default('baja');
            $table->string('motivo_necesidad')->nullable();

            // Requiere traslado
            $table->boolean('requiere_traslado')->default(false);

            // Centros y sedes destino (si hay traslado)
            $table->foreignId('centro_final_id')->nullable()->constrained('centros');
            $table->foreignId('sede_final_id')->nullable()->constrained('sedes');

            // Información personal
            $table->json('personal')->nullable(); // guarda [{nombre,documento,cargo},...]

            // Característica económica
            $table->string('fuente_financiacion')->nullable();
            $table->decimal('presupuesto_solicitado', 12, 2)->nullable();
            $table->decimal('presupuesto_aceptado', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('infraestructuras');
    }
}
