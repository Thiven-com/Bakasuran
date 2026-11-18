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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->string('profile_pic')->nullable()->after('password');
            $table->string('otp')->nullable()->after('profile_pic');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'profile_pic',
                'otp',
                'remember_token',
            ]);
        });
    }
};
