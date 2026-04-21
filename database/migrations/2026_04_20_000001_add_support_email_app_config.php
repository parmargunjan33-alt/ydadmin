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
        DB::table('app_configs')->insertOrIgnore([
            [
                'key' => 'support_email',
                'value' => 'support@ydapp.in',
                'description' => 'Support email for the application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('app_configs')->where('key', 'support_email')->delete();
    }
};
