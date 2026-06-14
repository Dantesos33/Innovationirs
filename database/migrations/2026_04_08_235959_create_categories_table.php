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
        Schema::dropIfExists('categories'); // Clean sweep guard clause

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_on_homepage')->default(false);

            // Add these two lines to match your seeder insertion parameters:
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('parts_count')->default(0);

            // Foreign relationship pointing to your media records table
            $table->foreignId('image_media_id')
                ->nullable()
                ->constrained('media_library')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('show_on_homepage');
        });
    }
};
