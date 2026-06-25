<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ficha', 20)->unique();
            $table->string('nombre_programa');
            $table->enum('nivel_formacion', [
                'tecnico', 'tecnologo', 'especializacion_tecnologica',
                'auxiliar', 'operario', 'curso_complementario'
            ]);
            $table->enum('modalidad', ['presencial', 'virtual', 'mixta']);
            $table->enum('estado', [
                'en_convocatoria', 'en_formacion', 'en_etapa_productiva',
                'certificado', 'cancelado'
            ])->default('en_convocatoria');
            $table->enum('jornada', ['diurna', 'nocturna', 'madrugada', 'fin_de_semana']);
            $table->unsignedBigInteger('centro_id');
            $table->unsignedBigInteger('sede_id');
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('numero_aprendices')->default(0);
            $table->timestamps();

            $table->foreign('centro_id')->references('id')->on('centros')->cascadeOnDelete();
            $table->foreign('sede_id')->references('id')->on('sedes')->cascadeOnDelete();
            $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas');
    }
};
