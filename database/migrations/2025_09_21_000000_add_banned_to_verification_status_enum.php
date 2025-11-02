<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to modify the enum to include 'banned'
        DB::statement("ALTER TABLE users MODIFY COLUMN verification_status ENUM('pending', 'approved', 'declined', 'banned') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update any 'banned' users to 'declined' before removing 'banned' from enum
        DB::statement("UPDATE users SET verification_status = 'declined' WHERE verification_status = 'banned'");

        // Remove 'banned' from the enum
        DB::statement("ALTER TABLE users MODIFY COLUMN verification_status ENUM('pending', 'approved', 'declined') DEFAULT 'pending'");
    }
};
