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
        // Jangan membuat tabel jika sudah ada, agar migrasi lain (penambahan kolom) tetap bisa berjalan
        if (!Schema::hasTable('time_logs')) {
            Schema::create('time_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('card_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamp('start_time');
                $table->timestamp('end_time')->nullable();
                $table->integer('duration_seconds')->nullable()->comment('Durasi dalam detik');
                $table->timestamps();

                $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->index(['card_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};
