
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->string('seating_type', 30)
                ->default('indoor')
                ->after('seating_capacity');

            $table->text('description')
                ->nullable()
                ->after('seating_type');

            $table->index(
                ['restaurant_id', 'status'],
                'restaurant_tables_restaurant_status_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropIndex('restaurant_tables_restaurant_status_idx');
            $table->dropColumn(['seating_type', 'description']);
        });
    }
};
