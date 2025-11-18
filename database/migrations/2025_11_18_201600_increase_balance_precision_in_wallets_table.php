<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Para PostgreSQL, usamos DB::statement para alterar a precisão do decimal
        DB::statement('ALTER TABLE wallets ALTER COLUMN balance TYPE DECIMAL(20, 2)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE wallets ALTER COLUMN balance TYPE DECIMAL(10, 2)');
    }
};

