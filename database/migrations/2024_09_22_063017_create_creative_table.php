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
        Schema::create('creative', function (Blueprint $table) {
            $table->id()->autoIncrement()->unsigned();
            $table->unsignedBigInteger('user_id');  // Reference to users table
            $table->string('article', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('title', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('description')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('video', 255)->nullable();  // nullable indicates it can be NULL
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
        Schema::dropIfExists('creative');
    }
};
