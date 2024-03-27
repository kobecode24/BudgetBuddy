<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials_and_receive_token()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testing@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testing@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'email',
                ],
                'token',
            ]
        ]);

    }
}
