<?php

namespace Tests\Feature\Progress;

use App\Company;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\User;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_dummy()
    {
        $this->markTestSkipped('ダミースキップ');
        $this->assertTrue(true);
    }

    // public function testUserRegisterProgress()
    // {
    //     $student = Students::first();
    //     $company = Company::find(1);
    //     $response = $this
    //         ->actingAs($student)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     // 2個目登録（1個目はseederで作成済み）
    //     $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status", "進捗を登録しました。");
    //     $this->assertDatabaseHas('progress', [
    //         'user_id' => $student->id,
    //         'entry_id' => 1,
    //         'action' => "面接",
    //         'state' => "合格",
    //     ]);

    //     // 3,4,5個目登録 (5個が作成限度)
    //     for($i = 2; $i < 5; $i++){
    //         $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //         $response->assertStatus(302);
    //         $response->assertRedirect('companies/'.$company->id);
    //         $response->assertSessionHas("status", "進捗を登録しました。");
    //     }
    //     // 6個目登録
    //     $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status-error", "進捗は5件までしか登録することができません。");
    // }

    // public function testUserRegisterProgressNotEnterd()
    // {
    //     $student = Students::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($student)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status-error", "エントリーしていないので進捗を登録できません。");
    // }

    // public function testTeacherRegisterProgress()
    // {
    //     $teacher = User::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHas("status-error", "あなたは教師なので進捗登録できません。");
    // }

    // public function testRegisterProgressNoActionAndStateAndAction_dateAndCompany_id()
    // {
    //     $teacher = Students::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->post(route('progress.store'), ['action' => '','state' => '', 'action_date' => '','company_id' => '']);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHasErrors(['action' => '活動内容は必須です。','state' => '状態は必須です。','action_date'=>'実施日は必須です。','company_id'=>'会社詳細ページから登録してください。']);
    // }

    // public function testRegisterProgressRegex()
    // {
    //     $teacher = Students::first();
    //     $company = Company::find(3);
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies/' . $company->id);
    //     $response->assertStatus(200);

    //     $response = $this->post(route('progress.store'), ['action' => '第一次面接','state' => '▽', 'action_date' => '2020/444/2323','company_id' => $company->id]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('companies/'.$company->id);
    //     $response->assertSessionHasErrors(['action' => '選択欄からお選びください。','state' => '選択欄からお選びください。','action_date'=>'日にちを入力してください。']);
    // }

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     Artisan::call('migrate:refresh');
    //     Artisan::call('db:seed');
    // }
}
