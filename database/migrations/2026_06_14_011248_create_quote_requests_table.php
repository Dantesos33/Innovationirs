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
        Schema::dropIfExists('quote_requests');

        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255);
            $table->string('phone', 30)->nullable();
            $table->string('company', 150)->nullable();
            $table->string('make', 100); // e.g., Caterpillar
            $table->string('model', 100); // e.g., 320D
            $table->string('serial_number', 100)->nullable();
            $table->string('part_number', 100)->nullable();
            $table->text('part_description');
            $table->unsignedInteger('quantity')->default(1);
            $table->text('notes')->nullable();

            // Matches status inputs: new, in_progress, quoted, closed_won
            $table->string('status', 50)->default('new');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
