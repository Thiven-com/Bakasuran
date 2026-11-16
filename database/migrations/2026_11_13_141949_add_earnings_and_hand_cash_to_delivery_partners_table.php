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
        Schema::table('delivery_partners', function (Blueprint $table) {
            // Delivery Partner Earnings
            $table->decimal('total_earnings', 12, 2)->default(0)->after('password');
            $table->decimal('pending_earnings', 12, 2)->default(0)->after('total_earnings');
            $table->decimal('settled_earnings', 12, 2)->default(0)->after('pending_earnings');

            // Earnings settlement limit configured by admin
            $table->decimal('earnings_settlement_limit', 12, 2)->default(0)->after('settled_earnings');

            // Hand Cash (COD Orders)
            $table->decimal('total_hand_cash', 12, 2)->default(0)->after('earnings_settlement_limit');
            $table->decimal('pending_hand_cash', 12, 2)->default(0)->after('total_hand_cash');
            $table->decimal('submitted_hand_cash', 12, 2)->default(0)->after('pending_hand_cash');

            // Maximum hand cash limit configured by admin
            $table->decimal('hand_cash_limit', 12, 2)->default(500)->after('submitted_hand_cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_partners', function (Blueprint $table) {
            //
        });
    }
};
