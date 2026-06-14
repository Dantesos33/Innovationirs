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
        Schema::dropIfExists('part_images'); // Clean sweep guard clause

        Schema::create('part_images', function (Blueprint $table) {
            $table->id();
            // Connects the image to a specific heavy equipment part
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();

            // Connects to your central media files registry
            $table->foreignId('media_library_id')->constrained('media_library')->cascadeOnDelete();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_main')->default(false); // Flags alternate vs primary photos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_images');
    }
};
