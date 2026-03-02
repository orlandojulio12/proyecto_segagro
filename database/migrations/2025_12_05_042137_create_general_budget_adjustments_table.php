<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('general_budget_adjustments', function (Blueprint $table) {
            $table->id('adjustment_id');

            // Relación con el presupuesto general
            $table->unsignedBigInteger('general_budget_id');
            $table->foreign('general_budget_id')
                ->references('id')
                ->on('general_budgets')
                ->onDelete('cascade');

            // Usuario que realizó el ajuste
            $table->unsignedBigInteger('user_id'); // sin FK

            // Monto del ajuste: puede ser positivo o negativo
            $table->decimal('amount', 15, 2);

            // Razón del ajuste
            $table->text('description')->nullable();

            // Fecha y hora real del ajuste
            $table->timestamps(); // created_at se usará como fecha de ajuste
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_budget_adjustments');
    }
};
