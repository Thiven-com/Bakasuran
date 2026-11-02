
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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('price_for_two', 10, 2)
                ->default(0)
                ->after('email');

            $table->unsignedTinyInteger('discount_percentage')
                ->default(0)
                ->after('price_for_two');

            $table->boolean('is_featured')
                ->default(false)
                ->after('discount_percentage');

            $table->unsignedInteger('sort_order')
                ->default(0)
                ->after('is_featured');

            $table->softDeletes()->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropSoftDeletes();

            $table->dropColumn([
                'price_for_two',
                'discount_percentage',
                'is_featured',
                'sort_order',
            ]);
        });
    }
};
