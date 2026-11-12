
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_food_orders', function (Blueprint $table) {
            $table->bigInteger('restaurant_table_booking_id')
                ->nullable()
                ->after('restaurant_table_id');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_food_orders', function (Blueprint $table) {
            $table->dropColumn('restaurant_table_booking_id');
        });
    }
};
