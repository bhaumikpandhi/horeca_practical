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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // This author can be reference from users table but not keeping it from users table as of now
            // because there are chances that some book's author is not available in our users table.
            $table->string('author');
            $table->date('published_date');
            $table->unsignedBigInteger('genre_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('genre_id')->references('id')->on('book_genres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
