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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('gst')->nullable();
            $table->string('address')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('landmark')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->boolean('default')->nullable()->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
