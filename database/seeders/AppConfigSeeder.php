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

        // Razorpay Key ID - Public key for client-side integration
        AppConfig::firstOrCreate(
            ['key' => 'razorpay_key_id'],
            [
                'value' => 'rzp_live_SjgjKHHhEuQ0dc',
                'description' => 'Razorpay Key ID for client-side payment integration.',
            ]
        );

        // Razorpay Key Secret - Private key for server-side integration
        AppConfig::firstOrCreate(
            ['key' => 'razorpay_key_secret'],
            [
                'value' => 'rvONLAcQgPZ2Sqq44Xjao6Yv',
                'description' => 'Razorpay Key Secret for server-side payment integration.',
            ]
        );
    }
}
