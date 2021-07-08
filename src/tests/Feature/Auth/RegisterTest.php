<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\User;


class RegisterTest extends TestCase
{
    use RefreshDatabase;
    public function testRegister()
    {
        $response = $this
            ->get('register')
            ->assertStatus(302)
            ->assertRedirect('register_confirm');

        $this->get('/register?teacher=true')
            ->assertStatus(200)
            ->assertViewIs('auth.register');

        $password = bcrypt('password');
        $user_data = [
            'name' => 'test太郎',
            'class' => 'ITエンジニア',
            'email' => 'testtaro@example.com',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post(route('register'), $user_data);
        // エラーメッセージがないこと
        $response->assertSessionHasNoErrors();

        // ユーザ登録後ログインされない設定にしたので確認
        $this->assertGuest();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoggedTransitionPage()
    {
        $response = $this
            ->actingAs(User::find(1))
            ->get('register')
            ->assertStatus(302)
            ->assertRedirect('home');

        $this->get('register')
            ->assertRedirect('home');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNoLoginTransitionPage()
    {
        $response = $this
            ->get('register')
            ->assertStatus(302)
            ->assertRedirect('register_confirm');

        $this->get('/register?teacher=true')
            ->assertStatus(200)
            ->assertViewIs('auth.register');
    }
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }

}
