<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_an_expense()
    {
        $user = User::factory()->create();

        $expenseData = [
            'amount' => 100,
            'description' => 'Test expense',
        ];

        $response = $this->actingAs($user)->postJson('/api/expenses', $expenseData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'amount' => 100,
            'description' => 'Test expense',
        ]);
    }
}
