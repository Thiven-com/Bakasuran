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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('link_url')->nullable();       // where to go when user taps
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamp('starts_at')->nullable();   // optional scheduling
            $table->timestamp('ends_at')->nullable();

            $table->boolean('active')->default(true);     // manual toggle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
