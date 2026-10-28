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
        Schema::create('restaurant_table_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique();

            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('restaurant_table_id');

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name', 150);
            $table->string('customer_mobile', 20);
            $table->string('customer_email', 150)->nullable();

            $table->date('booking_date');
            $table->time('booking_time');
            $table->time('end_time')->nullable();
            $table->unsignedInteger('guest_count');

            $table->text('special_requests')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_table_bookings');
    }
};
