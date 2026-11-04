<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurant_food_order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('restaurant_food_order_id');
            $table->unsignedBigInteger('product_variant_id');

            // Product details snapshot
            $table->string('product_name');
            $table->string('variant_name')->nullable();

            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_food_order_items');
    }
};
