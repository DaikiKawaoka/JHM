<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterConfirmTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoggedTransitionPage()
    {
        $response = $this
            ->actingAs(User::find(1)) // 追加
            ->get('register')
            ->assertStatus(302)
            ->assertRedirect('home');

        $this->get('register')
            ->assertRedirect('home'); // 追加(ここでの'home'は、ホーム画面で使われているビュー名)
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
            ->assertViewIs('auth.register'); // 追加(ここでの'home'は、ホーム画面で使われているビュー名)
    }

}
