<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_be_registered_through_the_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'teest@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'name',
                        'email',
                    ],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'teest@example.com',
        ]);
    }
}
