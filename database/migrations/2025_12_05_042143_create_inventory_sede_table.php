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
        Schema::create('inventory_sede', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sede_id')->constrained('sedes');
            $table->string('responsible_department');
            $table->foreignId('staff_name')->constrained('users', 'id');
            $table->text('image_inventory')->nullable();
            $table->text('inventory_description');
            $table->timestamp('record_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_sede');
    }
};
