<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    //ワークスペース機能一覧
    //ワークスペースの作成、編集、削除
    //ワークスペースの切り替え
    //メンバー（生徒の追加）
    //これらのページへのアクセスとpost,put,deleteのテスト
    //テストの実行方法
    //docker compose exec app bash → ./vendor/bin/phpunit

    //テストファイルの作成方法
    //例    docker compose exec app bash → php artisan make:test Workspace/~Test

    //できなさそうで後回しにするときは、
    //$this->markTestIncomplete('このテストは、まだ実装されていません。');
    //↑を関数の一番上に記述する


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->markTestSkipped('検証済みスキップ');
        $response = $this->get('/home');
        $response->assertStatus(302)
                 ->assertRedirect('/students/login'); // リダイレクト先を確認
        // 認証されていないことを確認
        $this->assertGuest();
    }
}
