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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. razorpay, stripe, cod
            $table->string('name');
            $table->enum('is_online',['yes','no'])->default('yes');
            $table->enum('status',['show','hide'])->default('show');
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->json('config')->nullable(); // 🔑 keys + settings stored here
            $table->decimal('fee_percent', 5, 2)->default(0);
            $table->decimal('fee_fixed', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
