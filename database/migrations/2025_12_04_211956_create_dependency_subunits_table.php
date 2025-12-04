<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dependency_subunits', function (Blueprint $table) {
            $table->id('subunit_id'); // PRIMARY KEY numeric

            // FOREIGN KEY to dependency_units
            $table->unsignedBigInteger('dependency_unit_id');
            $table->foreign('dependency_unit_id')
                ->references('dependency_unit_id')
                ->on('dependency_units')
                ->onDelete('cascade');

            $table->string('subunit_code'); // Example: 00, 10, 27...
            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dependency_subunits');
    }
};
