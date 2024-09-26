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
        Schema::create('fin_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // Reference to users table
            $table->char('user_type', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('account');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['new', 'approved', 'cancel'])->default('new');
            $table->timestamps();

            // Define a foreign key constraint referencing the primary key of the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_requests');
    }
};
