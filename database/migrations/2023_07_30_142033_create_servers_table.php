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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->foreignId('category_id')->constrained('categories'); // 'cascade' is the default value, but I like to be explicit
            $table->foreignId('country_id')->constrained('countries');
            $table->string('name');
            $table->string('host');
            $table->string('isp');
            $table->integer('limit')->default(0);
            $table->integer('current')->default(0);
            $table->integer('total')->default(0);
            $table->enum('status', ['online', 'offline']);
            $table->json('ports')->nullable();
            $table->longText('notes')->nullable();
            $table->string('token');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
