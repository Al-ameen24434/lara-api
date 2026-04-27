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
        Schema::create('tasks', function (Blueprint $table) {
             $table->id();                        // Auto-incrementing ID (1, 2, 3...)
            $table->foreignId('user_id')         // Links to the users table
                  ->constrained()               // Enforces the foreign key relationship
                  ->onDelete('cascade');         // If user is deleted, delete their tasks too
            $table->string('title');             // A short text column
            $table->text('description')->nullable(); // Long text, optional
            $table->enum('status', ['pending', 'in_progress', 'completed'])
                  ->default('pending');          // Only these 3 values allowed
            $table->timestamp('due_date')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
