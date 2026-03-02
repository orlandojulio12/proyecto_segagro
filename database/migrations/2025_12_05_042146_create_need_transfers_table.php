<?php

// database/migrations/xxxx_xx_xx_create_need_transfers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('need_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // funcionario que crea

            // Dependencia responsable
            $table->foreignId('dependencia_id')->nullable();

            // Centros y sedes iniciales y finales
            $table->foreignId('centro_inicial_id')->nullable()->constrained('centros');
            $table->foreignId('sede_inicial_id')->nullable()->constrained('sedes');
            $table->foreignId('centro_final_id')->nullable()->constrained('centros');
            $table->foreignId('sede_final_id')->nullable()->constrained('sedes');

            // Fechas
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            // Detalles
            $table->text('descripcion')->nullable();

            // Características adicionales
            $table->enum('nivel_riesgo', ['bajo', 'medio', 'alto'])->default('bajo');
            $table->enum('nivel_complejidad', ['baja', 'media', 'alta'])->default('baja');

            // Presupuesto
            $table->decimal('presupuesto_solicitado', 12, 2)->nullable();
            $table->decimal('presupuesto_aceptado', 12, 2)->nullable();

            // Opciones
            $table->boolean('requiere_personal')->default(false);
            $table->boolean('requiere_materiales')->default(false);

            $table->timestamps();
        });

        // Tabla pivote para personal
        Schema::create('need_transfer_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('need_transfer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cargo')->nullable();
        });

        // Tabla pivote para materiales
        Schema::create('need_transfer_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('need_transfer_id')
                ->constrained('need_transfers')
                ->onDelete('cascade');

            $table->foreignId('inventory_material_id')
                ->constrained('inventory_materials')
                ->onDelete('cascade');

            $table->integer('cantidad')->nullable();
            $table->string('tipo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('need_transfer_material');
        Schema::dropIfExists('need_transfer_user');
        Schema::dropIfExists('need_transfers');
    }
};
