<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('economies', function (Blueprint $table) {
            $table->id();

            // FK hacia la tabla "centros"
            $table->foreignId('centro_id')
                ->constrained('centros')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('start_date');
            $table->decimal('initial_budget', 15, 2);
            $table->decimal('used_budget', 15, 2)->default(0);
            $table->decimal('final_budget', 15, 2);
            $table->date('end_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economies');
    }
};
