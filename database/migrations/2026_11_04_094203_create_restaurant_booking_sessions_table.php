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
        Schema::create('restaurant_booking_sessions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('restaurant_id');

            $table->string('session_name', 100);

            // 0 = Sunday, 1 = Monday ... 6 = Saturday
            $table->unsignedTinyInteger('day_of_week');

            $table->time('start_time');
            $table->time('end_time');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('restaurant_id')
                ->references('id')
                ->on('restaurants')
                ->cascadeOnDelete();

            $table->index(
                ['restaurant_id', 'day_of_week', 'is_active'],
                'booking_sessions_restaurant_day_active_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_booking_sessions');
    }
};