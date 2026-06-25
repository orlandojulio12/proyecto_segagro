<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Renombra la columna de "dateTime" (DATE) a "date" (DATETIME)
        // Cambia el tipo a DATETIME para que TIMESTAMPDIFF funcione en tutelas
        DB::statement("ALTER TABLE `pqrs` CHANGE `dateTime` `date` DATETIME NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `pqrs` CHANGE `date` `dateTime` DATE NOT NULL");
    }
};
