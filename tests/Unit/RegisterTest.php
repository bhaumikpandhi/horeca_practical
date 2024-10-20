<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public string $registerUrl = 'api/v1/register';

    public function getUserFactory($params = [])
    {
        $user = User::factory()->make()->toArray();

        return array_merge($user, $params);
    }

    // Test successful registration
    public function test_successful_registration()
    {
        $factoryParams = ['password' => 'password123', 'confirm_password' => 'password123'];
        $response = $this->postJson($this->registerUrl, $this->getUserFactory($factoryParams));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email']
            ]);
    }

    // Test missing fields during registration
    public function test_missing_fields_in_registration()
    {
        $response = $this->postJson($this->registerUrl, [
            // Missing name, email, password
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    // Test registration with invalid email format
    public function test_invalid_email_format_in_registration()
    {
        $factoryParams = ['email' => 'Invalid email', 'password' => 'password123', 'confirm_password' => 'password123'];
        $response = $this->postJson($this->registerUrl, $this->getUserFactory($factoryParams));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    // Test password confirmation mismatch
    public function test_password_confirmation_mismatch()
    {
        $factoryParams = ['password' => 'password123', 'confirm_password' => 'password321'];
        $response = $this->postJson($this->registerUrl, $this->getUserFactory($factoryParams));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['confirm_password']);
    }

    // Test duplicate email
    public function test_duplicate_email()
    {
        $user = User::query()->first();
        $response = $this->postJson($this->registerUrl, $this->getUserFactory(['email' => $user->email]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
