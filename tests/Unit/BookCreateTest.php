<?php

namespace Tests\Unit;

use App\Models\Book\Book;
use App\Models\User;
use Tests\TestCase;

class BookCreateTest extends TestCase
{
    public string $createBookUrl = 'api/v1/books';

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

    // Test book creation without auth
    public function test_book_creation_without_auth()
    {
        $response = $this->postJson($this->createBookUrl, $this->getBookFactory());

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    // Test successful book creation
    public function test_successful_book_creation()
    {
        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->postJson($this->createBookUrl, $this->getBookFactory());

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Book created successfully'
            ]);
    }

    // Test missing fields during book creation
    public function test_missing_fields_in_book_creation()
    {
        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->postJson($this->createBookUrl, [
            // Missing title and author
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'author', 'published_date', 'genre_id']);
    }

    // Test invalid published date format
    public function test_invalid_published_date_format()
    {
        $params = ['published_date' => 'Invalid Date'];
        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->postJson($this->createBookUrl, $this->getBookFactory($params));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['published_date']);
    }

    // Test genre_id
    public function test_invalid_genre_id()
    {
        $params = ['genre_id' => 0];
        $this->setUser();
        $response = $this->actingAs($this->user, 'api')->postJson($this->createBookUrl, $this->getBookFactory($params));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['genre_id']);
    }
}
