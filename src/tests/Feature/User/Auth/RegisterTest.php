<?php

namespace Tests\Feature\User\Auth;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_register_user()
    {
        $response = $this->post('/register', [
            'name' => "test",
            'email' => "test@example.com",
            'password' => "test1234",
            'password_confirmation' => "test1234",
        ]);

        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas(User::class, [
            'name' => "test",
            'email' => "test@example.com",
        ]);
    }
}
