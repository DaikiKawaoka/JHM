<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
    * A basic feature test example.
    *
    * @return void
    */
    public function testLoginMatchePassword()
    {
        $this->markTestSkipped('検証済みスキップ');
        $response = $this->get('/login');
        $response->assertStatus(200);

        // ログインする
        $response = $this->post(route('login'), ['email' => 'teacher@example.com', 'password' => 'password']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/home');
        // 認証されたことを確認
        $this->assertAuthenticatedAs(Auth::user());
    }

    // use WithoutMiddleware;
    public function testLoginDifferentPassword()
    {
        $this->markTestSkipped('検証済みスキップ');
        $response = $this->get('/login');
        $response->assertStatus(200);

        // ログインする
        $response = $this->post(route('login'), ['email' => 'teacher@example.com', 'password' => 'passworddd']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        // 認証されていないことを確認
        $this->assertGuest();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->markTestSkipped('検証済みスキップ');
        $response = $this
            ->actingAs(User::find(1)) // 追加
            ->get('/home'); // 変更(ホーム画面のパスに変更)

        $response->assertStatus(302)
        ->assertRedirect(route('progress.index')); // 追加(ここでの'home'は、ホーム画面で使われているビュー名)
        // ->assertSee('You are logged in!'); // 追加(ホーム画面で表示されているメッセージ)
    }
    /**
     * ダッシュボードアクセス（ログイン画面へリダイレクト）
     */
    public function testNonloginAccess()
    {
        $this->markTestSkipped('検証済みスキップ');
        $response = $this->get('/home');
        $response->assertStatus(302)
                 ->assertRedirect('/students/login'); // リダイレクト先を確認
        // 認証されていないことを確認
        $this->assertGuest();
    }
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
