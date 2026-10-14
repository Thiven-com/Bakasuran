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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // references (no FK constraints)
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            // money
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('INR');

            // status/method/provider
            $table->string('status')->default('pending');   // pending|authorized|paid|failed|refunded
            $table->string('method')->nullable();           // cod|razorpay|stripe|...
            $table->string('provider')->nullable();         // razorpay|stripe|...
            $table->string('reference_no')->nullable();     // e.g. invoice id or your internal ref

            // provider identifiers & signature
            $table->string('provider_order_id')->nullable();
            $table->string('provider_payment_id')->nullable();
            $table->string('provider_signature')->nullable();

            // timestamps
            $table->timestamp('paid_at')->nullable();

            // anything extra (gateway payloads, logs)
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['status', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
