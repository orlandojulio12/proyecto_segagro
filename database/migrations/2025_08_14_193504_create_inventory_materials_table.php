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
        Schema::create('inventory_materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_id')->constrained('inventory_sede');
            $table->string('material_name');
            $table->integer('material_quantity');
            $table->string('material_type', 100)->nullable();
            $table->decimal('material_price', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_materials');
    }
};
