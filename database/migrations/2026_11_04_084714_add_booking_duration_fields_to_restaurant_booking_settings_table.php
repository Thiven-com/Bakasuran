<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_booking_settings', function (Blueprint $table) {
            $table->unsignedInteger('booking_duration')
                ->default(120)
                ->after('slot_duration');

            $table->unsignedInteger('cleaning_duration')
                ->default(15)
                ->after('booking_duration');

            $table->unsignedInteger('min_notice_minutes')
                ->default(60)
                ->after('max_advance_days');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_booking_settings', function (Blueprint $table) {
            $table->dropColumn([
                'booking_duration',
                'cleaning_duration',
                'min_notice_minutes',
            ]);
        });
    }
};