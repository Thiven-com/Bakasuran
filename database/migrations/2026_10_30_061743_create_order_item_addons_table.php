
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_addons', function (Blueprint $table) {

            $table->id();

            // Parent order item
            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            // Original add-on option reference
            // Nullable to preserve historical order records
            $table->foreignId('addon_option_id')
                ->nullable()
                ->constrained('addon_options')
                ->nullOnDelete();

            // Snapshot of group and option names
            $table->string('addon_group_name');
            $table->string('addon_name');

            // Price snapshot
            $table->decimal('unit_price', 10, 2)->default(0);

            // Add-on quantity per food item
            $table->unsignedInteger('quantity')->default(1);

            // Add-on subtotal per food item
            $table->decimal('subtotal', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_addons');
    }
};
