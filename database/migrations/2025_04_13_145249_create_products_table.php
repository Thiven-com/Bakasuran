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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug', 191)->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('brand_id')->nullable();
            $table->text('image')->nullable();
            $table->string('hover_image')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->bigInteger('tax_id')->nullable();
            $table->string('hsn_code')->nullable();
            $table->enum('status', ['show', 'hide'])->default('show')->nullable();
            $table->enum('is_feature', ['yes', 'no'])->default('yes')->nullable();
            $table->bigInteger('orders')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
