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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('category_id')->nullable()->after('product_id');
            $table->integer('brand_id')->nullable();
            $table->string('product_slug')->nullable();
            $table->string('stock')->nullable();
            $table->string('low_stock_alert')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('attribute_id')->nullable();
            // $table->string('sku')->nullable();
            $table->integer('product_min_order')->nullable();
            $table->integer('product_max_order')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            //
        });
    }
};
