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
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('semester_id')->constrained()->onDelete('cascade');
        $table->string('razorpay_order_id')->nullable();
        $table->string('razorpay_payment_id')->nullable();
        $table->string('razorpay_signature')->nullable();
        $table->integer('amount');
        $table->string('status')->default('pending');
        $table->timestamp('paid_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
        $table->unique(['user_id', 'semester_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
