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
                
            // Aquí cambiamos dependencias → dependency_subunits
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                ->references('subunit_id')
                ->on('dependency_subunits')
                ->onDelete('cascade');

            $table->bigInteger('total_budget');
            $table->bigInteger('spent_budget')->default(0);

            $table->integer('year');

            $table->bigInteger('manager_id')->unsigned();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_budgets');
    }
};
