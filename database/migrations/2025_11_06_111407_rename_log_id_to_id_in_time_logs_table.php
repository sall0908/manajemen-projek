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
        Schema::table('time_logs', function (Blueprint $table) {
            if (Schema::hasColumn('time_logs', 'log_id')) {
                $table->renameColumn('log_id', 'id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            if (Schema::hasColumn('time_logs', 'id')) {
                $table->renameColumn('id', 'log_id');
            }
        });
    }
};
