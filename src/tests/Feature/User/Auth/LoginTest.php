<?php

namespace Tests\Feature\User\Auth;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_user()
    {
        $user = User::find(1);

        $response = $this->post('/login', [
            'email' => "hoge@example.com",
            'password' => "hoge1234",
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_user_validate_email()
    {
        $response = $this->post('/login', [
            'email' => "",
            'password' => "hoge1234",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    public function test_login_user_validate_password()
    {
        $response = $this->post('/login', [
            'email' => "hoge@example.com",
            'password' => "",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    public function test_login_user_validate_user()
    {
        $response = $this->post('/login', [
            'email' => "hoge@example.com",
            'password' => "hoge5678",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message');

        $message = session('message');
        $this->assertEquals('ログイン情報が登録されていません', $message);
    }
}
