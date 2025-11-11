<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles_user') && !Schema::hasColumn('roles_user', 'updated_at')) {
            Schema::table('roles_user', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles_user') && Schema::hasColumn('roles_user', 'updated_at')) {
            Schema::table('roles_user', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
};