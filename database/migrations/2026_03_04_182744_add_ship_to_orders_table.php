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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('courier_order_id')->nullable()->after('status');
            $table->text('courier_shipment_id')->nullable()->after('courier_order_id');
            $table->text('awb')->nullable()->after('courier_shipment_id');   
            $table->string('carrier')->nullable()->after('awb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
