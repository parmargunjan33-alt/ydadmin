<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('app_configs')
            ->where('key', 'subscription_price')
            ->exists();

        if (! $exists) {
            DB::table('app_configs')->insert([
                'key' => 'subscription_price',
                'value' => '7500',
                'description' => 'Semester subscription price. Admin enters rupees; payment gateway receives paise.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('app_configs')
            ->where('key', 'subscription_price')
            ->update([
                'description' => 'Semester subscription price. Admin enters rupees; payment gateway receives paise.',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('app_configs')
            ->where('key', 'subscription_price')
            ->where('value', '7500')
            ->where('description', 'Semester subscription price. Admin enters rupees; payment gateway receives paise.')
            ->delete();
    }
};
