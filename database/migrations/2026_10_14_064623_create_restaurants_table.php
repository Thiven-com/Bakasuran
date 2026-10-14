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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();

            // Basic details
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Images
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            // Cuisine and location
            $table->string('cuisine_type')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Contact
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();

            // Operating hours
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();

            // Delivery and ratings
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedSmallInteger('delivery_time')->nullable();
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('minimum_order', 10, 2)->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_open')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
