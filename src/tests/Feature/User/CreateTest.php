<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTeacherCreatesStudent()
    {
        $teacher = User::where('is_teacher',1)->first();
        $response = $this
            ->actingAs($teacher)
            ->get('users/create');
        $response->assertStatus(200);

        $response = $this->post(route('users.store'), ['attend_num' =>5,'name' => 'User作成テスト太郎','email' => 'taro4@example.com', 'password' => 'password', 'password_confirmation' => 'password']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/home');

        $this->assertDatabaseHas('users', [
            'email' => 'taro4@example.com',
            'is_teacher' => 0,
            'teacher_id' => $teacher->id,
        ]);
    }

    public function testNonTeacherCreatesStudent()
    {
        $non_teacher = User::where('is_teacher',0)->first();

        $response = $this
            ->actingAs($non_teacher)
            ->get('users/create')
            ->assertStatus(302)
            ->assertRedirect('/home');

        $response = $this
        ->actingAs($non_teacher)
        ->post(route('users.store'), ['attend_num' =>6,'name' => 'User作成テスト太郎','email' => 'taro4@example.com', 'password' => 'password', 'password_confirmation' => 'password']);

        // dd($response);
        // メッセージがあることをチェック
        $response->assertSessionHas([
            'status-error' => 'あなたは教師ではないので生徒を登録することはできません。',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/home');

    }
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
