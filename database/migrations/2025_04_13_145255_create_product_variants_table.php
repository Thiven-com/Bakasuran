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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->text('slug')->nullable();
            $table->text('sku')->nullable();
            $table->text('image')->nullable();
            $table->string('hover_image')->nullable();
            $table->string('purchased_price')->nullable();
            $table->string('seller_price')->nullable();
            $table->string('actual_price')->nullable();
            $table->string('price')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->enum('today_deal', ['yes', 'no'])->nullable()->default('no');
            $table->boolean('preorder')->default(0);
            $table->integer('preorder_stock')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
