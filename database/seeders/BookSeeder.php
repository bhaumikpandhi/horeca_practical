<?php

namespace Database\Seeders;

use App\Models\Book\Book;
use App\Models\Book\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookGenres = [
            'Fiction', 'Non-Fiction', 'Mystery', 'Thriller', 'Science Fiction',
            'Fantasy', 'Romance', 'Horror', 'Historical', 'Biography',
            'Self-Help', 'Poetry', 'Drama', 'Adventure', 'Graphic Novel',
            'Children\'s', 'Young Adult', 'Memoir', 'Travel', 'Cookbook'
        ];

        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table
        Genre::query()->truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($bookGenres as $bookGenre) {
            Genre::factory()->create(['name' => $bookGenre]);
        }

        Book::factory()->count(20)->create();
    }
}
