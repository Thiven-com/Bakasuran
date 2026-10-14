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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable()->unique();
            $table->integer('address_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('mobile')->nullable()->unique();
            $table->double('wallet')->nullable()->default(0);
            $table->string('profile_pic')->nullable();
            $table->enum('mobile_verified', ['yes', 'no'])->nullable()->default('no');
            $table->enum('email_verified', ['yes', 'no'])->nullable()->default('no');
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('otp')->nullable();
            $table->string('firebase_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
