<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cards')) {
            Schema::table('cards', function (Blueprint $table) {
                if (!Schema::hasColumn('cards', 'due_date')) {
                    $table->date('due_date')->nullable();
                }
                if (!Schema::hasColumn('cards', 'priority')) {
                    $table->enum('priority', ['low', 'medium', 'high'])->nullable();
                }
                if (!Schema::hasColumn('cards', 'estimated_hours')) {
                    $table->decimal('estimated_hours', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('cards', 'actual_hours')) {
                    $table->decimal('actual_hours', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('cards', 'position')) {
                    $table->integer('position')->nullable();
                }
                if (!Schema::hasColumn('cards', 'created_by')) {
                    $table->unsignedInteger('created_by')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cards')) {
            Schema::table('cards', function (Blueprint $table) {
                if (Schema::hasColumn('cards', 'due_date')) {
                    $table->dropColumn('due_date');
                }
                if (Schema::hasColumn('cards', 'priority')) {
                    $table->dropColumn('priority');
                }
                if (Schema::hasColumn('cards', 'estimated_hours')) {
                    $table->dropColumn('estimated_hours');
                }
                if (Schema::hasColumn('cards', 'actual_hours')) {
                    $table->dropColumn('actual_hours');
                }
                if (Schema::hasColumn('cards', 'position')) {
                    $table->dropColumn('position');
                }
                if (Schema::hasColumn('cards', 'created_by')) {
                    $table->dropColumn('created_by');
                }
            });
        }
    }
};