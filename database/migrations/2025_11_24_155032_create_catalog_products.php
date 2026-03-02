<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_products', function (Blueprint $table) {
            $table->id();

            $table->integer('type_catalogo')->nullable();

            $table->string('type_code', 50)->nullable();
            $table->string('type_name')->nullable();

            $table->string('segment', 100)->nullable();
            $table->text('segment_description')->nullable();

            $table->string('family_code', 50)->nullable();
            $table->string('family_name')->nullable();

            $table->string('class_code', 50)->nullable();
            $table->string('class_name')->nullable();

            $table->integer('useful_life')->nullable();

            $table->string('sku')->nullable();
            $table->string('sku_description')->nullable();

            $table->integer('consecutive')->nullable();

            $table->text('element_description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_products');
    }
};
