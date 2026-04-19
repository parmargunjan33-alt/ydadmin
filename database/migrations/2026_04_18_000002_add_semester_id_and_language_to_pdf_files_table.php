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
        Schema::table('pdf_files', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->constrained()->onDelete('cascade')->after('subject_id');
            $table->enum('language', ['gujarati', 'english'])->default('english')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdf_files', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['semester_id', 'language']);
        });
    }
};
