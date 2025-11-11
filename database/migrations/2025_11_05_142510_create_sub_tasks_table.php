<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('sub_tasks')) {
            Schema::create('sub_tasks', function (Blueprint $table) {
                $table->id('sub_task_id');
                $table->unsignedInteger('card_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->timestamps();

                // Hindari FK untuk menghindari mismatch tipe jika tabel lama berbeda
                // $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tasks');
    }
};
