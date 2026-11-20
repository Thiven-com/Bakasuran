<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {

            // Existing restaurants remain approved
            $table->string('approval_status')
                ->default('approved')
                ->after('is_active');

            $table->text('rejection_reason')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {

            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'approved_at',
                'approved_by',
            ]);

        });
    }
};