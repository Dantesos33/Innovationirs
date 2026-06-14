<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('career_postings');

        Schema::create('career_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department')->nullable(); // Added
            $table->string('location')->nullable();
            $table->string('job_type')->default('full_time'); // Added (matches full_time string)
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable(); // Added
            $table->string('salary_range')->nullable(); // Added
            $table->string('apply_email')->nullable(); // Added
            $table->boolean('is_active')->default(true);
            $table->timestamp('posted_at')->nullable(); // Added
            $table->timestamp('expires_at')->nullable(); // Added
            $table->timestamp('closing_date')->nullable(); // Retained from earlier mockup
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_postings');
    }
};
