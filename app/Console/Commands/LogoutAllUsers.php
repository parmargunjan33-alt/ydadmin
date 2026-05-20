<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutAllUsers extends Command
{
    protected $signature = 'users:logout-all {--force : Run without confirmation in production}';

    protected $description = 'Logout every application user by deleting Sanctum tokens and clearing device locks.';

    public function handle(): int
    {
        if (app()->isProduction() && ! $this->option('force')) {
            if (! $this->confirm('This will logout every application user. Continue?')) {
                $this->warn('Global logout cancelled.');

                return self::SUCCESS;
            }
        }

        $userCount = User::count();
        $tokenCount = PersonalAccessToken::where('tokenable_type', User::class)->count();

        DB::transaction(function (): void {
            PersonalAccessToken::where('tokenable_type', User::class)->delete();

            User::query()->update([
                'device_uuid' => null,
                'logout_all_at' => now(),
            ]);
        });

        $this->info("Logged out {$userCount} users.");
        $this->line("Deleted {$tokenCount} user access tokens.");

        return self::SUCCESS;
    }
}
