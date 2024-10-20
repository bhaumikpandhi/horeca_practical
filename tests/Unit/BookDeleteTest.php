<?php

namespace Tests\Unit;

use App\Models\Book\Book;
use App\Models\User;
use Tests\TestCase;

class BookDeleteTest extends TestCase
{
    public string $deleteBookUrl = 'api/v1/books/';

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

    // Test successful book deletion by authenticated user
    public function test_successful_book_deletion()
    {
        $book = Book::factory()->create();

        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->deleteJson($this->deleteBookUrl . $book->id);

        $response->assertStatus(200);
    }

    // Test unauthorized book deletion (without authentication)
    public function test_unauthorized_book_deletion()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson($this->deleteBookUrl . $book->id);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    // Test trying to delete a non-existing book
    public function test_delete_non_existing_book()
    {
        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->deleteJson($this->deleteBookUrl . 0);

        $response->assertStatus(404);
    }
}
