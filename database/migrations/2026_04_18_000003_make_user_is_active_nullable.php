<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY is_active TINYINT(1) NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('UPDATE users SET is_active = 1 WHERE is_active IS NULL');
        DB::statement('ALTER TABLE users MODIFY is_active TINYINT(1) NOT NULL DEFAULT 1');
    }
};
