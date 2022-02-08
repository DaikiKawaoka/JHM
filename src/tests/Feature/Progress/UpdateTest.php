<?php

namespace Tests\Feature\Progress;

use App\Company;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\User;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    // 進捗情報を更新した場合のテスト
    public function testWhenProgressInfoIsUpdated()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status", "進捗を変更しました。");
        $this->assertDatabaseHas('progress', [
            'student_id' => $student->id,
            'entry_id' => 1,
            'action' => "説明会",
            'state' => "欠席",
        ]);
    }
    // 未登録の進捗情報が更新された場合のテスト
    public function testWhenUnregisteredProgressInfoIsUpdated()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->put(route('progress.update',['progress' => 10000]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status-error", "進捗が登録されていないのでこの処理はできません。");
    }

    // エントリーしていない会社IDで進捗情報が更新される場合のテスト。
    public function testWhenProgressInfoIsUpdatedForCompanyIdNotEntered()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '欠席', 'action_date' => '2021-12-10','company_id' => 12,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302)
                    ->assertRedirect('companies/'. 12)
                    ->assertSessionHas("status-error", "エントリーしていないのでこの処理はできません。");
    }

    // 先生が進捗情報を更新した場合のテスト
    public function testWhenTheTeacherUpdatesTheProgressInfo()
    {
        $teacher = User::first();
        $company = Company::first();
        $response = $this
            ->actingAs($teacher)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies');
        $response->assertSessionHas("status-error", "アクセス権限がありません");
    }

    // データがない状態で進捗情報を更新した場合のテスト
    public function testWhenProgressInfoIsUpdatedWithNoData()
    {
        $teacher = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($teacher)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '', 'action_date' => '','company_id' => '']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHasErrors(['state' => '状態は必須です。','company_id'=>'会社詳細ページから変更してください。']);
    }

    // 進捗情報更新時の正規表現テスト
    public function testOfRegex()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '▽', 'action_date' => '2020/444/2323','company_id' => "aaa"]);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHasErrors(['state' => '選択欄からお選びください。','company_id'=>'会社IDが不正です。']);
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
