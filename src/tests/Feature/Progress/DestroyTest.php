<?php

namespace Tests\Feature\Progress;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Company;
use App\Progress;
use App\Students;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    // 進捗情報を削除した場合のテスト
    public function testWhenProgressInfoIsDeleted()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $progress = Progress::first();
        $response->assertStatus(200);

        $response = $this->delete(route('progress.destroy',['progress' => $progress->id]));
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status", "進捗（" . $progress->action . "）を削除しました。");
        // 削除されたかテスト
        $this->assertDeleted($progress);
    }

    // 未登録の進捗情報を削除した場合のテスト
    public function testWhenUnregisteredProgressInfoIsDeleted()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->delete(route('progress.destroy',['progress' => 100000]));
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status-error", '進捗の削除処理に失敗しました。');
    }

    // 他の生徒の進捗情報を削除した場合のテスト
    public function testWhenProgressInfoOfOtherStudentIsDeleted()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->delete(route('progress.destroy',['progress' => 100]));
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
