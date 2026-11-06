
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_groups', function (Blueprint $table) {

            $table->id();

            // Add-on group name
            $table->string('name');

            // Unique-friendly URL identifier
            $table->string('slug')->unique();

            // Single or multiple selection
            $table->enum('selection_type', [
                'single',
                'multiple'
            ])->default('multiple');

            // Is customer required to select?
            $table->boolean('is_required')->default(false);

            // Minimum selections
            $table->unsignedInteger('min_select')->default(0);

            // Maximum selections
            $table->unsignedInteger('max_select')->nullable();

            // Active / inactive
            $table->enum('status', [
                'show',
                'hide'
            ])->default('show');

            // Display order
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_groups');
    }
};
