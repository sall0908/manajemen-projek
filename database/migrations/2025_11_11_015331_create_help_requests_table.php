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
        if (!Schema::hasTable('help_requests')) {
            Schema::create('help_requests', function (Blueprint $table) {
                $table->id('request_id');
                $table->unsignedBigInteger('subtask_id')->nullable();
                $table->integer('user_id'); // Developer or Designer who reported
                $table->text('issue_description');
                $table->enum('status', ['pending', 'in_progress', 'fixed', 'completed'])->default('pending');
                $table->integer('resolved_by')->nullable(); // Team Lead who resolved
                $table->text('resolution_notes')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->dateTime('resolved_at')->nullable();

                // Foreign keys
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->foreign('subtask_id')->references('sub_task_id')->on('sub_tasks')->onDelete('cascade');
                $table->foreign('resolved_by')->references('user_id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_requests');
    }
};
