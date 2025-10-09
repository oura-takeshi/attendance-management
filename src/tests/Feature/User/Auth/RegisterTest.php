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

    public function test_register_user_validate_name()
    {
        $response = $this->post('/register', [
            'name' => "",
            'email' => "test@example.com",
            'password' => "test1234",
            'password_confirmation' => "test1234",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals('お名前を入力してください', $errors->first('name'));
    }

    public function test_register_user_validate_email()
    {
        $response = $this->post('/register', [
            'name' => "test",
            'email' => "",
            'password' => "test1234",
            'password_confirmation' => "test1234",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    public function test_register_user_validate_password()
    {
        $response = $this->post('/register', [
            'name' => "test",
            'email' => "test@example.com",
            'password' => "",
            'password_confirmation' => "test1234",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    public function test_register_user_validate_password_under7()
    {
        $response = $this->post('/register', [
            'name' => "test",
            'email' => "test@example.com",
            'password' => "test123",
            'password_confirmation' => "test1234",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードは8文字以上で入力してください', $errors->first('password'));
    }

    public function test_register_user_validate_confirm_password()
    {
        $response = $this->post('/register', [
            'name' => "test",
            'email' => "test@example.com",
            'password' => "test1234",
            'password_confirmation' => "test5678",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password_confirmation');

        $errors = session('errors');
        $this->assertEquals('パスワードと一致しません', $errors->first('password_confirmation'));
    }
}
