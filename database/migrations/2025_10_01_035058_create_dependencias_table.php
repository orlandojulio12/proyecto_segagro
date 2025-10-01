<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dependencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Ej: 'Sistemas', 'Administración'
            $table->string('descripcion')->nullable(); // Breve descripción opcional
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dependencias');
    }
};
