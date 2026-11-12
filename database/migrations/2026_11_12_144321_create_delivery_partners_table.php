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
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('partner_code')->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('mobile', 20)->unique();
            $table->string('otp', 4)->nullable();
            $table->string('alternate_mobile', 20)->nullable();
            $table->string('profile_image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();

            // Address Information
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('landmark')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('pincode', 20)->nullable();

            // Location Information
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Vehicle Information
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('vehicle_model')->nullable();

            // Document Information
            $table->string('aadhar_number', 20)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->string('driving_license_number')->nullable();
            $table->string('driving_license_image')->nullable();
            $table->string('aadhar_front_image')->nullable();
            $table->string('aadhar_back_image')->nullable();
            $table->string('pan_image')->nullable();
            $table->string('vehicle_rc_image')->nullable();

            // Bank Information
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->string('upi_id')->nullable();

            // Account Information
            $table->string('password')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('verification_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->boolean('is_available')->default(false);

            $table->enum('status', [
                'active',
                'inactive',
                'blocked'
            ])->default('active');

            $table->text('rejection_reason')->nullable();

            // Login Information
            $table->timestamp('last_login_at')->nullable();
            $table->string('device_token')->nullable();

            // Other Information
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_partners');
    }
};
