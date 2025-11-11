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
        // Hindari error jika tabel sudah ada atau tipe FK tidak cocok
        if (!Schema::hasTable('card_time_logs')) {
            Schema::create('card_time_logs', function (Blueprint $table) {
                $table->id();
                // Samakan tipe dengan kolom pada tabel terkait (di project ini umumnya unsignedInteger)
                $table->unsignedInteger('card_id');
                $table->unsignedInteger('user_id');
                $table->timestamp('start_time');
                $table->timestamp('end_time')->nullable();
                $table->integer('duration_seconds')->nullable()->comment('Durasi dalam detik');
                $table->timestamps();

                // Jangan pasang FK untuk menghindari incompatibility antar tipe; gunakan index saja
                $table->index(['card_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_time_logs');
    }
};
