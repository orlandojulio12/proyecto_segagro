<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dependency_budgets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('economy_id')
                ->constrained('economies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // FK hacia la tabla "dependencias"
            $table->foreignId('dependencia_id')
                ->constrained('dependencias')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('initial_budget', 15, 2);
            $table->decimal('used_budget', 15, 2)->default(0);
            $table->decimal('final_budget', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependency_budgets');
    }
};
