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
        Schema::create('restaurant_food_orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number', 50)->unique();

            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('restaurant_table_id');
            $table->unsignedBigInteger('customer_id');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);

            $table->string('status')->default('pending');
            // pending, accepted, preparing, ready_to_serve,
            // served, completed, cancelled

            $table->text('special_instructions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_food_orders');
    }
};
