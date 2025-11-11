<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('time_logs')) {
            Schema::create('time_logs', function (Blueprint $table) {
                $table->increments('log_id');
                $table->unsignedInteger('card_id')->nullable();
                $table->unsignedInteger('subtask_id')->nullable();
                $table->unsignedInteger('user_id')->nullable();
                $table->timestamp('start_time')->nullable();
                $table->timestamp('end_time')->nullable();
                $table->integer('duration_minutes')->nullable();
                $table->integer('duration_seconds')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();

                // No FK constraints to avoid type mismatches; add indexes for lookup
                $table->index(['card_id']);
                $table->index(['subtask_id']);
                $table->index(['user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};