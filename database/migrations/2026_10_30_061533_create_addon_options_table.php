
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_options', function (Blueprint $table) {

            $table->id();

            // Parent add-on group
            $table->foreignId('addon_group_id')
                ->constrained('addon_groups')
                ->cascadeOnDelete();

            // Add-on name
            $table->string('name');

            // Free or paid add-on
            $table->decimal('price', 10, 2)->default(0);

            // Enable or disable quantity-based stock tracking
            $table->boolean('track_stock')->default(false);

            // Available quantity
            $table->unsignedInteger('stock')->nullable();

            // Low stock warning threshold
            $table->unsignedInteger('low_stock_alert')->nullable();

            // Manual availability
            $table->boolean('is_available')->default(true);

            // Display order
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_options');
    }
};
