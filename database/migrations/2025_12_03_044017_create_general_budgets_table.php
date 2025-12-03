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

            $table->foreignId('manager_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_budgets');
    }
};
