<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('department_budgets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('general_budget_id')
                  ->constrained('general_budgets')
                  ->onDelete('cascade');

            $table->foreignId('department_id')
                  ->constrained('dependencias')
                  ->onDelete('cascade');

            $table->bigInteger('total_budget');
            $table->bigInteger('spent_budget')->default(0);

            $table->integer('year');

            $table->foreignId('manager_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_budgets');
    }
};
