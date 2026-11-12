<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_food_orders', function (Blueprint $table) {

            $table->timestamp('bill_generated_at')
                ->nullable()
                ->after('status');

            $table->string('payment_method')
                ->nullable()
                ->after('bill_generated_at');

            $table->string('payment_status')
                ->default('pending')
                ->after('payment_method');

            $table->string('payment_transaction_id')
                ->nullable()
                ->after('payment_status');

            $table->timestamp('paid_at')
                ->nullable()
                ->after('payment_transaction_id');

        });
    }

    public function down(): void
    {
        Schema::table('restaurant_food_orders', function (Blueprint $table) {
            $table->dropColumn([
                'bill_generated_at',
                'payment_method',
                'payment_status',
                'payment_transaction_id',
                'paid_at',
            ]);
        });
    }
};