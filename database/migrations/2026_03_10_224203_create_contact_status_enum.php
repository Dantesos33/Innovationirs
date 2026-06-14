<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('contact_messages');

        DB::statement("
            CREATE TABLE contact_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                status ENUM('new','open','in_progress','resolved') DEFAULT 'new'
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE contact_messages
            MODIFY status ENUM('new','in_progress','resolved')
            DEFAULT 'new'
        ");
    }
};
