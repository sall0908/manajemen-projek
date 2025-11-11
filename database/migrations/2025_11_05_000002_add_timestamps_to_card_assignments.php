<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('card_assignments')) {
            Schema::table('card_assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('card_assignments', 'started_at')) {
                    $table->timestamp('started_at')->nullable();
                }
                if (!Schema::hasColumn('card_assignments', 'completed_at')) {
                    $table->date('completed_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('card_assignments')) {
            Schema::table('card_assignments', function (Blueprint $table) {
                if (Schema::hasColumn('card_assignments', 'started_at')) {
                    $table->dropColumn('started_at');
                }
                if (Schema::hasColumn('card_assignments', 'completed_at')) {
                    $table->dropColumn('completed_at');
                }
            });
        }
    }
};