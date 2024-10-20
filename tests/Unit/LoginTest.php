<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public string $loginUrl = 'api/v1/login';

    // test successful login
    public function test_successful_login()
    {
        // Attempt login
        $response = $this->postJson($this->loginUrl, [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        // Assert success
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
            ]);
    }

    // Test invalid login credentials
    public function test_invalid_login_credentials()
    {
        $response = $this->postJson($this->loginUrl, [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
    }

    // Test missing fields
    public function test_missing_fields_in_login()
    {
        $response = $this->postJson($this->loginUrl, [
            // Missing email and password
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    // Test invalid email format
    public function test_invalid_email_format_in_login()
    {
        $response = $this->postJson($this->loginUrl, [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
