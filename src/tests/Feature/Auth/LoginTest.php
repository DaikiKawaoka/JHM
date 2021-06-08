<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this
            ->actingAs(User::find(1)) // 追加
            ->get('/home'); // 変更(ホーム画面のパスに変更)

        $response->assertStatus(200)
        ->assertViewIs('home') // 追加(ここでの'home'は、ホーム画面で使われているビュー名)
        ->assertSee('You are logged in!'); // 追加(ホーム画面で表示されているメッセージ)
    }
}
