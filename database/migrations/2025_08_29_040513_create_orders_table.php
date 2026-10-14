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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();

            // money
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('delivery_total', 12, 2)->default(0); // sum of shipment delivery charges
            $table->decimal('grand_total', 12, 2);

            // payment
            $table->string('invoice_id')->unique();
            $table->string('payment_method')->nullable();          // cod|razorpay|stripe|...
            $table->string('payment_status')->default('pending');  // pending|authorized|paid|failed|refunded
            

            // addresses snapshots
            $table->json('shipping_address'); // {name, phone, line1, city, ...}
            $table->json('billing_address');  // same shape

            // status lifecycle
            $table->string('status')->default('pending');          // pending|confirmed|shipped|completed|cancelled

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
