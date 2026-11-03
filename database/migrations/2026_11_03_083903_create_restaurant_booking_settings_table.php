
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_booking_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('restaurant_id')->unique();

            $table->unsignedInteger('slot_duration')->default(60);

            $table->unsignedInteger('max_advance_days')->default(30);

            $table->unsignedInteger('min_guests')->default(1);

            $table->unsignedInteger('max_guests')->default(20);

            $table->boolean('is_booking_enabled')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_booking_settings');
    }
};
