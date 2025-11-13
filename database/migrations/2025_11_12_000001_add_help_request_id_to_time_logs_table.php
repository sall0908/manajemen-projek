<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('time_logs')) {
            Schema::table('time_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('time_logs', 'help_request_id')) {
                    $table->unsignedBigInteger('help_request_id')->nullable()->after('subtask_id');
                    $table->index('help_request_id');
                    // Add FK if table exists
                    if (Schema::hasTable('help_requests')) {
                        $table->foreign('help_request_id')->references('request_id')->on('help_requests')->onDelete('cascade');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('time_logs')) {
            Schema::table('time_logs', function (Blueprint $table) {
                if (Schema::hasColumn('time_logs', 'help_request_id')) {
                    // drop FK if exists
                    try {
                        $table->dropForeign(['help_request_id']);
                    } catch (\Exception $e) {
                        // ignore if FK doesn't exist
                    }
                    $table->dropIndex(['help_request_id']);
                    $table->dropColumn('help_request_id');
                }
            });
        }
    }
};
