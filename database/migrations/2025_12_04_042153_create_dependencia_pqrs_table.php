<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dependencia_pqr', function (Blueprint $table) {
            $table->bigIncrements('id_dependencia'); // ID autoincrement personalizado
            $table->string('name');                  // Nombre de la dependencia
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependencia_pqr');
    }
};