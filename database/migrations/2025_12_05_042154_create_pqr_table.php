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
            $table->string('title');                     // Título
            $table->date('dateTime');                        // Fecha
            $table->text('description');                 // Descripción
            $table->string('responsible');               // Responsable
            $table->unsignedBigInteger('concepto_id'); // Relación con concepto_pqr
            $table->string('pdf_path')->nullable();      // Adjuntar PDF
            $table->unsignedBigInteger('user_id');
            $table->boolean('state')->default(false);    // false = pendiente, true = completada
            $table->timestamps();

            $table->foreign('concepto_id')
                ->references('id_concepto')
                ->on('concepto_pqr')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqrs'); // ← nombre correcto
    }
};
