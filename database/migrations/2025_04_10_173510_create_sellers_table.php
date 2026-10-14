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
      Schema::create('sellers', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();

        $table->string('email')->unique();
        $table->string('mobile')->nullable();
        $table->string('password')->nullable();

        $table->string('otp')->nullable();
        $table->longText('firebase_token')->nullable();

        $table->longText('description')->nullable();


        $table->string('address')->nullable();
        $table->string('locality')->nullable();

        $table->enum('application_status', [
                'pending',
                'approved',
                'rejected',
                'banned',

            ])->default('pending');
        $table->enum('kyc_status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',

            ])->default('pending');


        $table->enum('device_type', [
                'ios',
                'android'
            ]);
        $table->text('device_token')->nullable();
        $table->string('device_id')->nullable();
        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
