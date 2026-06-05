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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->integer('xp')->default(0);
            $table->integer('streak')->default(0);
            $table->json('badges')->nullable();
            $table->json('completed_lessons')->nullable();
            $table->json('completed_labs')->nullable();
            $table->json('completed_quizzes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['xp', 'streak', 'badges', 'completed_lessons', 'completed_labs', 'completed_quizzes']);
        });
    }
};
