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
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('seller_id')->index();

            $table->string('shipment_id')->unique();     // e.g., SHP-20250829-XYZ
            $table->string('carrier')->nullable();       // Delhivery, Bluedart, etc.
            $table->string('tracking_no')->nullable();

            $table->decimal('delivery_charge', 12, 2)->default(0);
            $table->string('status')->default('pending'); // pending|packed|shipped|delivered|cancelled

            // (optional) per-shipment address override (usually same as order shipping)
            $table->json('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
    }
};
