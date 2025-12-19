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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->text('content');
            $table->string('anonymous_token', 64)->unique(); // Hashed token for anonymous identification
            $table->enum('status', ['pending', 'approved', 'flagged'])->default('pending');
            $table->text('moderation_note')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();
            
            // Important: NO user_id column for anonymity
            $table->index('category_id');
            $table->index('status');
            $table->index('anonymous_token');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
