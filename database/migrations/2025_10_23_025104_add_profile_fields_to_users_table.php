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
            $table->text('bio')->nullable()->after('phone');
            $table->string('location')->nullable()->after('bio');
            $table->string('company_name')->nullable()->after('location');
            $table->string('license_number')->nullable()->after('company_name');
            $table->json('contact_preferences')->nullable()->after('license_number');
            $table->json('social_links')->nullable()->after('contact_preferences');
            $table->boolean('profile_completed')->default(false)->after('social_links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
