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
        Schema::table('issues', function (Blueprint $table) {
            $table->string('location')->nullable()->after('category');
            $table->enum('contact_method', ['phone', 'email', 'text'])->nullable()->after('location');
            $table->string('available_times')->nullable()->after('contact_method');
            $table->json('photos')->nullable()->after('available_times');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn(['location', 'contact_method', 'available_times', 'photos']);
        });
    }
};
