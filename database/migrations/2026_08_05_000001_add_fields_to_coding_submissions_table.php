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
        Schema::table('coding_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('coding_submissions', 'project_id')) {
                $table->string('project_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('coding_submissions', 'challenge_id')) {
                $table->string('challenge_id')->nullable()->after('project_id');
            }
            if (!Schema::hasColumn('coding_submissions', 'error_log')) {
                $table->text('error_log')->nullable()->after('memory');
            }
            if (!Schema::hasColumn('coding_submissions', 'runtime_ms')) {
                $table->integer('runtime_ms')->nullable()->after('error_log');
            }
            if (!Schema::hasColumn('coding_submissions', 'memory_kb')) {
                $table->integer('memory_kb')->nullable()->after('runtime_ms');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coding_submissions', function (Blueprint $table) {
            $table->dropColumn(['project_id', 'challenge_id', 'error_log', 'runtime_ms', 'memory_kb']);
        });
    }
};
