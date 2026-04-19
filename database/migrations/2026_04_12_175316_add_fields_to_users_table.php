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
    Schema::table('users', function (Blueprint $table) {
        $table->string('mobile', 15)->unique()->nullable()->after('email');
        $table->string('device_id')->nullable()->after('mobile');
        $table->string('device_name')->nullable()->after('device_id');
        $table->boolean('mobile_verified')->default(false)->after('device_name');
        $table->timestamp('mobile_verified_at')->nullable()->after('mobile_verified');
        $table->unsignedBigInteger('university_id')->nullable()->after('mobile_verified_at');
        $table->unsignedBigInteger('course_id')->nullable()->after('university_id');
        $table->unsignedBigInteger('semester_id')->nullable()->after('course_id');
        $table->boolean('is_active')->default(true)->after('semester_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
