<?php

namespace Tests\Feature\Progress;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Company;
use App\Progress;
use App\User;

class DestroyTest extends TestCase
{
    use RefreshDatabase;
    public function testDeleteProgress()
    {
        $user = User::find(2);
        $company = Company::find(1);
        $response = $this
            ->actingAs($user)
            ->get('companies/' . $company->id);
        $progress = Progress::find(1);
        $response->assertStatus(200);

        $response = $this->delete(route('progress.destroy',['progress' => $progress->id]));
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status", "進捗（" . $progress->action . "）を削除しました。");
        // 削除されたかテスト
        $this->assertDeleted($progress);
    }

    public function testDeleteProgressUnregisterProgress()
    {
        $user = User::find(2);
        $company = Company::find(1);
        $response = $this
            ->actingAs($user)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->delete(route('progress.destroy',['progress' => 100]));
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status-error", '進捗の削除処理に失敗しました。');
    }

    public function testDeleteOtherUserProgress()
    {
        $user = User::find(2);
        $company = Company::find(1);
        $response = $this
            ->actingAs($user)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->delete(route('progress.destroy',['progress' => 3]));
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status-error", '他人の進捗は削除できません。');
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
