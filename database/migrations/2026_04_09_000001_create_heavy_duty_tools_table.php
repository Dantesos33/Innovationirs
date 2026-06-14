<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('heavy_duty_tools');

        Schema::create('heavy_duty_tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->string('part_number')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('specifications')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_order'])->default('in_stock');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->unsignedBigInteger('primary_image_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('ships_worldwide')->default(true);
            $table->decimal('weight_lbs', 8, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('primary_image_id')->nullable()->references('id')->on('media_library')->nullOnDelete();
            $table->index(['status', 'is_featured']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heavy_duty_tools');
    }
};
