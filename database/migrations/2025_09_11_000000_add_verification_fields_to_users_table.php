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
        Schema::table('users', function (Blueprint $table) {
            // Add verification fields if they don't exist
            if (!Schema::hasColumn('users', 'verification_status')) {
                $table->enum('verification_status', ['pending', 'approved', 'declined'])->default('pending')->after('email_verified_at');
            }

            if (!Schema::hasColumn('users', 'verification_id')) {
                $table->string('verification_id')->nullable()->unique()->after('verification_status');
            }

            if (!Schema::hasColumn('users', 'document_type')) {
                $table->enum('document_type', [
                    'philippine_id',
                    'drivers_license',
                    'sss_gsis',
                    'passport',
                    'birth_certificate',
                    'other'
                ])->nullable()->after('verification_id');
            }

            if (!Schema::hasColumn('users', 'valid_id_path')) {
                $table->string('valid_id_path')->nullable()->after('document_type');
            }

            if (!Schema::hasColumn('users', 'valid_id_back_path')) {
                $table->string('valid_id_back_path')->nullable()->after('valid_id_path');
            }

            if (!Schema::hasColumn('users', 'verification_notes')) {
                $table->text('verification_notes')->nullable()->after('valid_id_back_path');
            }

            if (!Schema::hasColumn('users', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verification_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verification_status',
                'verification_id',
                'document_type',
                'valid_id_path',
                'valid_id_back_path',
                'verification_notes',
                'verified_at'
            ]);
        });
    }
};
