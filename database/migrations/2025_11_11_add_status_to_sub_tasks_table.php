<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('sub_tasks', 'status')) {
                $table->enum('status', ['todo', 'in_progress', 'review', 'done'])
                      ->default('todo')
                      ->after('is_completed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('sub_tasks', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
