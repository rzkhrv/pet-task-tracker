<?php

declare(strict_types=1);

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
        Schema::create('task_notifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->text('message');

            $table->dateTime('created_at');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('task_id')
                ->references('id')
                ->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_notifications');
    }
};
