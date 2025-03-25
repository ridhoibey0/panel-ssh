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
        Schema::create('server_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained('servers')->onUpdate('cascade')->onDelete('cascade'); // 'cascade' is the default value, but I like to be explicit
            $table->foreignId('role_id')->constrained('roles'); // 'cascade' is the default value, but I like to be explicit
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_daily', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_prices');
    }
};
