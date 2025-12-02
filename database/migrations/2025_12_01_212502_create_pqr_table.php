<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pqrs', function (Blueprint $table) {
        $table->id();
        $table->string('title');                     // Titulo
        $table->date('date');                        // Fecha
        $table->text('description');                 // Descripción
        $table->string('responsible');               // Responsable
        $table->string('dependency');                // Dependencia
        $table->string('pdf_path')->nullable();      // Adjuntar PDF
        $table->unsignedBigInteger('user_id');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqr');
    }
};
