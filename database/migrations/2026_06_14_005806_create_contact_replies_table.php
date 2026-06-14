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
        Schema::dropIfExists('contact_replies');

        Schema::create('contact_replies', function (Blueprint $table) {
            $table->id();
            // References the parent message column
            $table->foreignId('contact_id')->constrained('contact_messages')->cascadeOnDelete();
            // References the admin replying
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();

            $table->text('message');
            $table->boolean('is_admin')->default(true);
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_replies');
    }
};
