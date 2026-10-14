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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('date')->nullable();
            $table->string('title')->nullable();
            $table->string('description', 5000)->nullable();
            $table->string('category')->nullable();
            $table->string('image', 2000)->nullable();
            $table->string('status')->nullable();
            $table->string('comment', 3000)->nullable();
            $table->string('priority', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
