<?php

namespace Tests\Feature\Progress;

use App\Company;
use App\Entry;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\User;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function testStudentRegisterProgress()
    {
        $student = Students::first();
        $entry = Entry::where('company_id','1')->where('student_id', $student->id)->first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . 1);
        $response->assertStatus(200);

        // 2個目登録（1個目はseederで作成済み）
        $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => 1,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'. 1);
        $response->assertSessionHas("status", "進捗を登録しました。");
        $this->assertDatabaseHas('progress', [
            'student_id' => $student->id,
            'entry_id' => $entry->id,
            'action' => "面接",
            'state' => "合格",
            'action_date' => '2021-06-10'
        ]);

        // 3,4,5個目登録 (5個が作成限度)
        for($i = 2; $i < 5; $i++){
            $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => 1,'company_type' => 'teacher_created_company']);
            $response->assertStatus(302);
            $response->assertRedirect('companies/'. 1);
            $response->assertSessionHas("status", "進捗を登録しました。");
        }
        // 6個目登録
        $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => 1,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'. 1);
        $response->assertSessionHas("status-error", "進捗は5件までしか登録することができません。");
    }

    public function testStudentRegisterProgressWithNotEnterd()
    {
        $student = Students::first();
        //エントリーしていない会社を取得
        $company = Company::find(12);
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHas("status-error", "エントリーしていないので進捗を登録できません。");
    }

    public function testTeacherRegisterProgress()
    {
        $teacher = User::first();
        $company = Company::first();
        $response = $this
            ->actingAs($teacher)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->post(route('progress.store'), ['action' => '面接','state' => '合格', 'action_date' => '2021-06-10','company_id' => $company->id]);
        $response->assertStatus(302);
        $response->assertRedirect('companies');
        $response->assertSessionHas("status-error", "処理権限がありません");
    }

    public function testToRegisterProgressInfoWithNoData()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->post(route('progress.store'), ['action' => '','state' => '', 'action_date' => '','company_id' => '','company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHasErrors(['action' => '活動内容は必須です。','state' => '状態は必須です。','action_date'=>'実施日は必須です。','company_id'=>'会社詳細ページから登録してください。']);
    }

    // 進捗情報登録時の正規表現テスト
    public function testOfRegex()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this
            ->actingAs($student)
            ->get('companies/' . $company->id);
        $response->assertStatus(200);

        $response = $this->post(route('progress.store'), ['action' => '第一次面接','state' => '▽', 'action_date' => '2020/444/2323','company_id' => $company->id,'company_type' => 'teacher_created_company']);
        $response->assertStatus(302);
        $response->assertRedirect('companies/'.$company->id);
        $response->assertSessionHasErrors(['action' => '選択欄からお選びください。','state' => '選択欄からお選びください。','action_date'=>'日にちを入力してください。']);
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
