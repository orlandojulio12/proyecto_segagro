<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('concepto_pqr', function (Blueprint $table) {
            $table->bigIncrements('id_concepto');        // ID autoincrement personalizado
            $table->string('name');                      // Nombre del concepto
            $table->unsignedBigInteger('dependencia_id'); // Relación con dependencia
            $table->timestamps();

            $table->foreign('dependencia_id')
                ->references('id_dependencia')
                ->on('dependencia_pqr')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concepto_pqr');
    }
};