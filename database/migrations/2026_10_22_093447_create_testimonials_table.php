<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('image')->nullable();
            $table->string('name', 255);
            $table->string('city', 100)->nullable();

            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('description');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamps();

            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
