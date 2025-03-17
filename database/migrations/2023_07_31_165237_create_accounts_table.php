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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('server_id')->constrained('servers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('username');
            $table->string('password')->nullable();
            $table->string('uuid')->unique()->nullable();
            $table->enum('tipe', [1, 2, 3]);
            $table->dateTime('expired_at')->nullable();
            $table->json('detail');
            $table->enum('status', [0, 1, 2])->comment('0: inactive, 1: active, 2: stopped')->default('1');
            $table->decimal('charge', 10, 2)->default(0);
            $table->dateTime('stopped_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
