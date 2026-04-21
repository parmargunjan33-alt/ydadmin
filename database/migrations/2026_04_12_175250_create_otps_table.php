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
    Schema::create('otps', function (Blueprint $table) {
        $table->id();
        $table->string('mobile', 15)->nullable();
        $table->string('email')->nullable();
        $table->string('otp', 6);
        $table->string('purpose')->default('register');
        $table->boolean('is_used')->default(false);
        $table->timestamp('expires_at');
        $table->timestamps();
        $table->index('mobile');
        $table->index('email');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
