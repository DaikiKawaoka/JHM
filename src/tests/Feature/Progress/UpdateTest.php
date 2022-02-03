<?php

namespace Tests\Feature\Progress;

use App\Company;
use App\Progress;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\User;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_dummy()
    {
        $this->markTestSkipped('ダミースキップ');
        $this->assertTrue(true);
    }

    // public function testUserUpdateProgress()
    // {
    //     $student = Students::first();
    //     $company = Company::find(1);
    //     $response = $this
    //         ->actingAs($student)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status", "進捗を変更しました。");
    //     $this->assertDatabaseHas('progress', [
    //         'student_id' => $student->id,
    //         'entry_id' => 1,
    //         'action' => "説明会",
    //         'state' => "欠席",
    //     ]);
    // }

    // public function testUserUpdateProgressUnregisterProgress()
    // {
    //     $student = Students::first();
    //     $company = Company::find(1);
    //     $response = $this
    //         ->actingAs($student)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->put(route('progress.update',['progress' => 3]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status-error", "進捗が登録されていないのでこの処理はできません。");
    // }

    // public function testUserUpdateProgressNotEnterd()
    // {
    //     $student = Students::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($student)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status-error", "エントリーしていないのでこの処理はできません。");
    // }

    // public function testTeacherUpdateProgress()
    // {
    //     $teacher = User::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '欠席', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status-error", "あなたは教師なのでこの処理はできません。");
    // }

    // public function testUpdateProgressNoStateAndAction_dateAndCompany_id()
    // {
    //     $teacher = Students::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '', 'action_date' => '','company_id' => '']);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHasErrors(['state' => '状態は必須です。','action_date'=>'実施日は必須です。','company_id'=>'会社詳細ページから変更してください。']);
    // }

    // public function testUpdateProgressRegex()
    // {
    //     $teacher = Students::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->put(route('progress.update',['progress' => 1]), ['state' => '▽', 'action_date' => '2020/444/2323','company_id' => "aaa"]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHasErrors(['state' => '選択欄からお選びください。','action_date'=>'日にちを入力してください。','company_id'=>'会社IDが不正です。']);
    // }

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     Artisan::call('migrate:refresh');
    //     Artisan::call('db:seed');
    // }
}
