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
        Schema::create('fin_additionals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Additional fields
            $table->string('for_user'); // For username
            $table->string('for_user_type');
            $table->enum('type', ['in', 'out'])->default('in'); // Type with default 'in'
            $table->decimal('amount', 15, 2); // Amount with precision
            $table->text('description')->nullable(); // Description (optional)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_additionals');
    }
};
