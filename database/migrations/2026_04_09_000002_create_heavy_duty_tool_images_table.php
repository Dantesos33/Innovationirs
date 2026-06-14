<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('heavy_duty_tool_images');

        Schema::create('heavy_duty_tool_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tool_id');
            $table->unsignedBigInteger('media_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('alt_text')->nullable();
            $table->timestamps();

            $table->foreign('tool_id')->references('id')->on('heavy_duty_tools')->cascadeOnDelete();
            $table->foreign('media_id')->references('id')->on('media_library')->cascadeOnDelete();
            $table->index(['tool_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heavy_duty_tool_images');
    }
};
