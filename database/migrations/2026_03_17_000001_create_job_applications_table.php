<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('job_applications');

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_posting_id')->constrained('career_postings')->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255);
            $table->string('phone', 30)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cv_path', 500)->nullable();
            $table->string('cv_original_name', 255)->nullable();
            $table->enum('status', ['new', 'reviewed', 'shortlisted', 'rejected', 'hired'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
