<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salida_ferreteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('centro_id')->constrained('centros')->cascadeOnDelete();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->date('fecha_salida');
            $table->string('f14')->nullable();
            $table->timestamps();
        });

        Schema::create('salida_ferreteria_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_ferreteria_id')
                ->constrained('salida_ferreteria')
                ->cascadeOnDelete();
            $table->foreignId('inventory_material_id')
                ->constrained('inventory_materials')
                ->cascadeOnDelete();
            $table->decimal('cantidad', 10, 2)->default(0);
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salida_ferreteria_details');
        Schema::dropIfExists('salida_ferreteria');
    }
};
