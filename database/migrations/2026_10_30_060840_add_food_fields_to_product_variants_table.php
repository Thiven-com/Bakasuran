
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            // Manual availability
            $table->boolean('is_available')->default(true);

            // Preparation time in minutes
            $table->unsignedSmallInteger('preparation_time')->nullable();

            // Display order
            $table->unsignedInteger('sort_order')->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            $table->dropColumn([
                'is_available',
                'preparation_time',
                'sort_order',
            ]);

        });
    }
};
