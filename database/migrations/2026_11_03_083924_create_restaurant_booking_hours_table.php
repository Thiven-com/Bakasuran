
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_booking_hours', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('restaurant_id');

            // 0 = Sunday, 1 = Monday, ... 6 = Saturday
            $table->unsignedTinyInteger('day_of_week');

            $table->time('opening_time')->nullable();

            $table->time('closing_time')->nullable();

            $table->boolean('is_closed')->default(false);

            $table->timestamps();

            $table->unique(
                ['restaurant_id', 'day_of_week'],
                'restaurant_booking_hours_restaurant_day_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_booking_hours');
    }
};
