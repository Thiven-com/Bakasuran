
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_item_addons', function (Blueprint $table) {

            $table->id();

            // Parent cart item
            $table->foreignId('cart_item_id')
                ->constrained('cart_items')
                ->cascadeOnDelete();

            // Original add-on option
            $table->foreignId('addon_option_id')
                ->nullable()
                ->constrained('addon_options')
                ->nullOnDelete();

            // Snapshot of add-on name
            $table->string('addon_name');

            // Snapshot of add-on price
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
        Schema::dropIfExists('cart_item_addons');
    }
};
