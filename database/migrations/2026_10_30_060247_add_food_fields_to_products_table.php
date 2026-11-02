
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Food classification
            $table->string('food_type', 20)
                ->default('veg');

            // Preparation time in minutes
            $table->unsignedSmallInteger('preparation_time')
                ->nullable();

            // Manual availability
            $table->boolean('is_available')
                ->default(true);

            // Recommended food
            $table->boolean('is_recommended')
                ->default(false);

            // Display sorting
            $table->unsignedInteger('sort_order')
                ->default(0);

            // Allergy information
            $table->text('allergen_info')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn([
                'food_type',
                'preparation_time',
                'is_available',
                'is_recommended',
                'sort_order',
                'allergen_info',
            ]);

        });
    }
};
