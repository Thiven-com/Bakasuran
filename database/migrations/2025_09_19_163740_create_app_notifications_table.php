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
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // recipient
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('type')->nullable(); // e.g. order, system, promo
            $table->boolean('is_promotional')->default(false);
            $table->string('image')->nullable(); // promotional image (path)
            $table->string('icon')->nullable();  // small icon path (or CSS class)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->json('data')->nullable(); // extra payload (order_id, url, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
