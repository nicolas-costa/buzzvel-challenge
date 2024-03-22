<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessfulAuthentication(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/auth/login', $credentials);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    public function testInvalidCredentials(): void
    {
        $invalidCredentials = [
            'email' => 'test@example.com',
            'password' => 'wrong_password'
        ];

        $response = $this->postJson('/api/auth/login', $invalidCredentials);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
