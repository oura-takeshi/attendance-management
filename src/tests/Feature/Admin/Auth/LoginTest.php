<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_admin()
    {
        $admin = Admin::find(1);

        $response = $this->post('/admin/login', [
            'email' => "admin@example.com",
            'password' => "admin1234",
        ]);

        $response->assertRedirect('/admin/attendance/list');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_login_admin_validate_email()
    {
        $response = $this->post('/login', [
            'email' => "",
            'password' => "admin1234",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    public function test_login_admin_validate_password()
    {
        $response = $this->post('/login', [
            'email' => "admin@example.com",
            'password' => "",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    public function test_login_admin_validate_admin()
    {
        $response = $this->post('/login', [
            'email' => "admin@example.com",
            'password' => "admin5678",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message');

        $message = session('message');
        $this->assertEquals('ログイン情報が登録されていません', $message);
    }
}
