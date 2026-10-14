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
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id')->constrained()->nullable();

            // core
            $table->string('type', 20); // image|video|gif|pdf|3d|audio
            $table->string('mime', 100)->nullable(); // e.g. image/jpeg, video/mp4
            $table->string('url');                  // public URL or storage path
            $table->string('thumbnail_url')->nullable(); // for videos/pdfs/3d previews
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);

            // optional metadata
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('duration')->nullable(); // seconds for video/audio
            $table->json('meta')->nullable(); // any extra info (codec, bitrate, etc.)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
