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
            if (!Schema::hasColumn('time_logs', 'sub_task_id')) {
                $table->unsignedBigInteger('sub_task_id')->nullable()->after('card_id');
                $table->foreign('sub_task_id')->references('sub_task_id')->on('sub_tasks')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            if (Schema::hasColumn('time_logs', 'sub_task_id')) {
                $table->dropForeign(['sub_task_id']);
                $table->dropColumn('sub_task_id');
            }
        });
    }
};
