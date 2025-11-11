<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            // Tambahkan kolom project_id kalau belum ada
            if (!Schema::hasColumn('cards', 'project_id')) {
                // Tambahkan kolom tanpa constraint FK untuk menghindari incompatibility
                $table->unsignedInteger('project_id')->after('card_id');
            }
        });
    }

    public function down(): void
    {
        // Turunkan dengan aman tanpa bergantung pada nama constraint
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Schema::table('cards', function (Blueprint $table) {
            if (Schema::hasColumn('cards', 'project_id')) {
                $table->dropColumn('project_id');
            }
        });
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
};
