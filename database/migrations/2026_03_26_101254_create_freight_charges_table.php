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
        Schema::create('freight_charges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shipping_zone_id')->nullable();
            $table->decimal('min_weight', 10, 3)->nullable();
            $table->decimal('max_weight', 10, 3)->nullable();
            $table->decimal('charge', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freight_charges');
    }
};
