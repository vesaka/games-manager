<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id');
            $table->integer('user_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->mediumInteger('score');
            $table->json('payload')->nullable();
            $table->json('info')->nullable();
            $table->string('hash', 128)->nullable();
            $table->string('status', 64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('game_sessions');
    }
};
