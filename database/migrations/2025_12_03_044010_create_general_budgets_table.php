<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('general_budgets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sede_id')
                ->constrained('sedes')
                ->onDelete('cascade');


            $table->bigInteger('total_budget');
            $table->bigInteger('spent_budget')->default(0);

            $table->integer('year');
            $table->string('resolution')->nullable();

            $table->bigInteger('manager_id')->unsigned();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('department_budgets');
        Schema::dropIfExists('general_budgets');

        Schema::enableForeignKeyConstraints();
    }
};
