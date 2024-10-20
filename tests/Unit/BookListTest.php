<?php

namespace Tests\Unit;

use App\Models\Book\Book;
use Tests\TestCase;

class BookListTest extends TestCase
{
    public string $booksUrl = 'api/v1/books';

    // Test successful retrieval of the books list
    public function test_successful_books_list_retrieval()
    {
        // Attempt to get the list of books
        $response = $this->getJson($this->booksUrl);

        // Assert success
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);
    }

    // Test empty books list
    public function test_empty_books_list()
    {
        $filter = ['filter' => ['author' => 'asjbdashbd']];
        $response = $this->getJson($this->booksUrl . '?' . http_build_query($filter));

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }
}
