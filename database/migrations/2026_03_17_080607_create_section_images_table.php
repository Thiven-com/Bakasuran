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
        Schema::create('section_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('section_id')->nullable();
            $table->string('section_slug')->nullable();
            $table->string('image', 1000)->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_images');
    }
};
