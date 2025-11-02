<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable()->after('is_on_time');
            $table->timestamp('rejected_at')->nullable()->after('verified_at');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('rejected_at');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('verified_by');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['verified_at', 'rejected_at', 'verified_by', 'rejected_by', 'rejection_reason']);
        });
    }
};
