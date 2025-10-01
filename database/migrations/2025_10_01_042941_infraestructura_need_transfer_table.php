<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('infraestructura_need_transfer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infraestructura_id')
                  ->constrained('infraestructuras')
                  ->cascadeOnDelete();

            $table->foreignId('need_transfer_id')
                  ->constrained('need_transfers')
                  ->cascadeOnDelete();

            // Puedes agregar campos extra si quieres
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infraestructura_need_transfer');
    }
};
