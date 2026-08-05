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
        Schema::create('coding_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('challenge_id')->nullable();
            $table->string('project_id')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->string('language');
            $table->text('code');
            $table->enum('status', ['pending', 'passed', 'failed', 'error'])->default('pending');
            $table->decimal('runtime', 8, 4)->nullable();
            $table->integer('memory')->nullable();
            $table->integer('runtime_ms')->nullable();
            $table->integer('memory_kb')->nullable();
            $table->text('error_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coding_submissions');
    }
};
