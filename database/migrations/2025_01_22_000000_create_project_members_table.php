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
        if (!Schema::hasTable('project_members')) {
            Schema::create('project_members', function (Blueprint $table) {
                $table->id();
                // Gunakan tipe yang konsisten dengan tabel induk
                $table->unsignedInteger('project_id');
                $table->unsignedInteger('user_id');
                // Kolom pivot yang digunakan oleh relasi
                $table->string('role', 50)->nullable();
                $table->timestamp('joined_at')->nullable();
                $table->timestamps();

                // Hindari constraint FK karena ketidakcocokan tipe antar tabel eksisting
                $table->unique(['project_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};