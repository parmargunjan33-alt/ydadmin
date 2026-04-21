<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Seeder;

class AppConfigSeeder extends Seeder
{
    public function run(): void
    {
        $config = AppConfig::firstOrCreate(
            ['key' => 'subscription_price'],
            [
                'value' => '7500',
                'description' => 'Semester subscription price. Admin enters rupees; payment gateway receives paise.',
            ]
        );

        if (! $config->description) {
            $config->update([
                'description' => 'Semester subscription price. Admin enters rupees; payment gateway receives paise.',
            ]);
        }
    }
}
