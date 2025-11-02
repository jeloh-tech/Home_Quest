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
        Schema::create('verification_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action'); // 'submitted', 'approved', 'rejected', 'resubmitted'
            $table->string('status')->nullable(); // 'pending', 'approved', 'rejected'
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable(); // Admin who performed the action
            $table->json('metadata')->nullable(); // Additional data like document paths, timestamps
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');

            $table->index(['user_id', 'created_at']);
            $table->index('action');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_histories');
    }
};
