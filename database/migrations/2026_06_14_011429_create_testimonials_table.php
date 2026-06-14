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
        Schema::dropIfExists('testimonials'); // Clean sweep guard clause

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('reviewer_name', 150);
            $table->string('reviewer_title', 150)->nullable();
            $table->string('company', 150)->nullable();
            $table->string('location', 150)->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5); // Handles 1-5 ratings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('source', 50)->default('direct'); // google, direct, Facebook etc.
            $table->timestamps();

            // Prevent duplicate reviews from the same person for the same company
            $table->unique(['reviewer_name', 'company']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
