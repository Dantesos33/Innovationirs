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
        Schema::dropIfExists('part_machine_fits');

        Schema::create('part_machine_fits', function (Blueprint $table) {
            $table->id();

            // Foreign Keys (Ensure column types match your parts and equipment_models tables)
            $table->foreignId('part_id')->constrained()->onDelete('cascade');
            $table->foreignId('model_id')->constrained('equipment_models')->onDelete('cascade');

            // Custom pivot fields requested by your query
            $table->integer('year_start')->nullable();
            $table->integer('year_end')->nullable();
            $table->string('serial_start')->nullable();
            $table->string('serial_end')->nullable();
            $table->text('fit_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_machine_fits');
    }
};
