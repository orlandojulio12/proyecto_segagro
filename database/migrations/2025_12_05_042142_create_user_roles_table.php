<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('user_roles', function (Blueprint $table) {
        $table->id();


        // Relación con users (columna id)
       $table->foreignId('user_id')
      ->constrained('users') // Laravel asume columna 'id'
      ->onDelete('cascade');

        // Relación con roles (asumiendo que roles tiene 'id')
        $table->foreignId('role_id')
              ->constrained('roles') // Laravel asume 'id' por defecto
              ->onDelete('cascade');

        $table->timestamp('assigned_at')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
