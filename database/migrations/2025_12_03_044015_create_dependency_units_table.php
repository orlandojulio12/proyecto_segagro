<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dependency_units', function (Blueprint $table) {
            $table->id('dependency_unit_id'); // PRIMARY KEY numeric

            $table->string('short_name');    // Example: DESPLAZADOS, FORMACIÓN
            $table->string('full_name');     // Full descriptive name
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dependency_units');
    }
};
