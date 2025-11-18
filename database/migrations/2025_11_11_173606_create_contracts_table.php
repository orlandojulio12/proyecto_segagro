<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number');
            
            $table->foreignId('hiring_modality_id')
                ->constrained('hiring_modalities')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('contractor_name');
            $table->string('contractor_nit');
            $table->text('contract_object');

            $table->foreignId('contract_type_id')
                ->constrained('contract_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('sede_id')
                ->constrained('sedes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('start_date');
            $table->date('initial_end_date');
            $table->date('extension_date')->nullable();
            
            $table->decimal('initial_value', 15, 2);
            $table->decimal('addition_value', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
