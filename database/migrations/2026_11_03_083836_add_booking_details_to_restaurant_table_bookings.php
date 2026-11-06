
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_table_bookings', function (Blueprint $table) {
            $table->string('seating_type', 30)
                ->nullable()
                ->after('guest_count');

            $table->string('occasion', 100)
                ->nullable()
                ->after('seating_type');

            $table->timestamp('confirmed_at')
                ->nullable()
                ->after('status');

            $table->timestamp('cancelled_at')
                ->nullable()
                ->after('confirmed_at');

            $table->text('admin_notes')
                ->nullable()
                ->after('cancelled_at');

            $table->index(
                ['restaurant_id', 'booking_date', 'status'],
                'table_bookings_restaurant_date_status_idx'
            );

            $table->index(
                ['restaurant_table_id', 'booking_date', 'status'],
                'table_bookings_table_date_status_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_table_bookings', function (Blueprint $table) {
            $table->dropIndex('table_bookings_restaurant_date_status_idx');
            $table->dropIndex('table_bookings_table_date_status_idx');

            $table->dropColumn([
                'seating_type',
                'occasion',
                'confirmed_at',
                'cancelled_at',
                'admin_notes',
            ]);
        });
    }
};
