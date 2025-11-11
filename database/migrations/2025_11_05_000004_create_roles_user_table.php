<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles_user')) {
            Schema::create('roles_user', function (Blueprint $table) {
                $table->bigIncrements('role_id');
                $table->string('role_name', 50)->default('20');
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('roles_user');
    }
};