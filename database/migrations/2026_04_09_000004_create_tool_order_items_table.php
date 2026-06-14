<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('tool_order_items');

        Schema::create('tool_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('tool_id')->nullable(); // nullable so item survives tool deletion
            $table->string('tool_name');                       // snapshot at time of purchase
            $table->string('tool_sku')->nullable();
            $table->string('tool_part_number')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('line_total', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('tool_orders')->cascadeOnDelete();
            $table->foreign('tool_id')->references('id')->on('heavy_duty_tools')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_order_items');
    }
};
