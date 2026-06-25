<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nullify existing values so FK change doesn't fail on orphan rows
        \DB::table('fichas')->update(['instructor_id' => null]);
        \DB::table('horarios')->update(['instructor_id' => null]);

        Schema::table('fichas', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->foreign('instructor_id')->references('id')->on('instructores')->nullOnDelete();
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->foreign('instructor_id')->references('id')->on('instructores')->nullOnDelete();
        });
    }

    public function down(): void
    {
        \DB::table('fichas')->update(['instructor_id' => null]);
        \DB::table('horarios')->update(['instructor_id' => null]);

        Schema::table('fichas', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
