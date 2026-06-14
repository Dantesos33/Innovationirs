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
        Schema::dropIfExists('parts');

        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->string('part_number', 100)->index();
            $table->string('oem_part_number', 100)->nullable();
            $table->string('sku', 100)->unique();

            $table->foreignId('make_id')->constrained('makes')->cascadeOnDelete();
            $table->foreignId('equipment_type_id')->constrained('equipment_types')->cascadeOnDelete();
            $table->foreignId('primary_image_id')->nullable()->constrained('media_library')->nullOnDelete();

            $table->string('condition_type', 50)->default('new');
            $table->string('status', 50)->default('active');
            $table->string('stock_status', 50)->default('in_stock');
            $table->unsignedInteger('stock_quantity')->default(0);

            // Add this line right here to catch the views analytics property:
            $table->unsignedInteger('views')->default(0);

            $table->decimal('price', 12, 2)->default(0.00);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('weight_lbs', 10, 2)->nullable();

            $table->string('warranty_type', 50)->default('none');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('ships_worldwide')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('california_prop65')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
