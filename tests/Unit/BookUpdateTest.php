<?php

namespace Tests\Unit;

use App\Models\Book\Book;
use App\Models\User;
use Tests\TestCase;

class BookUpdateTest extends TestCase
{
    public string $updateBookUrl = 'api/v1/books/';

    public User $user;

    public function setUser()
    {
        $this->user = User::where('email', 'admin@admin.com')->first();
    }

    public function getBookFactory($params = [])
    {
        $book = Book::factory()->make()->toArray();

        return array_merge($book, $params);
    }

    // Test successful book update by authenticated user
    public function test_successful_book_update()
    {
        $book = Book::factory()->create();

        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->putJson($this->updateBookUrl . $book->id, $this->getBookFactory());

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Book updated successfully'
            ]);
    }

    // Test unauthorized book update (without authentication)
    public function test_unauthorized_book_update()
    {
        $book = Book::factory()->create();

        $response = $this->putJson($this->updateBookUrl . $book->id, $this->getBookFactory());

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    // Test missing fields in book update with authentication
    public function test_missing_fields_in_book_update()
    {
        $book = Book::factory()->create();

        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->putJson($this->updateBookUrl . $book->id, [
            // Missing required params
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'author', 'published_date', 'genre_id']);
    }
}
