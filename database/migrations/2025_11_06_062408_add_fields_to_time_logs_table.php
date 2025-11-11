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
            if (!Schema::hasColumn('time_logs', 'card_id')) {
                $table->unsignedBigInteger('card_id')->after('id');
                $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
            }
            if (!Schema::hasColumn('time_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('card_id');
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('time_logs', 'start_time')) {
                $table->timestamp('start_time')->after('user_id');
            }
            if (!Schema::hasColumn('time_logs', 'end_time')) {
                $table->timestamp('end_time')->nullable()->after('start_time');
            }
            if (!Schema::hasColumn('time_logs', 'duration_seconds')) {
                $table->integer('duration_seconds')->nullable()->after('end_time')->comment('Durasi dalam detik');
            }
            if (!Schema::hasColumn('time_logs', 'created_at')) {
                $table->timestamps();
            }
            
            $table->index(['card_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            //
        });
    }
};
