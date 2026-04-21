<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            // Check if email column doesn't exist, then add it
            if (!Schema::hasColumn('otps', 'email')) {
                $table->string('email')->nullable()->after('mobile');
                $table->index('email');
            }
            
            // Check if mobile column exists and change it to nullable
            if (Schema::hasColumn('otps', 'mobile')) {
                // Modify mobile to be nullable
                $table->string('mobile', 15)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            if (Schema::hasColumn('otps', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
