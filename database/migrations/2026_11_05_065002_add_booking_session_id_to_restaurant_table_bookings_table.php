<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_table_bookings', function (Blueprint $table) {
            $table->foreignId('booking_session_id')
                ->nullable()
                ->after('restaurant_id')
                ->constrained('restaurant_booking_sessions')
                ->restrictOnDelete();

            $table->index('booking_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_table_bookings', function (Blueprint $table) {
            $table->dropForeign(['booking_session_id']);
            $table->dropIndex(['booking_session_id']);
            $table->dropColumn('booking_session_id');
        });
    }
};