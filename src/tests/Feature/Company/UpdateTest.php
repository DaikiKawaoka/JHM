<?php

namespace Tests\Feature\Company;

use App\Company;
use App\Students;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpdateName()
    {
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=>'テスト株式会社10',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'テスト株式会社10',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdatePrefecture(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=> $company->name,
            'prefecture' => '北海道',
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => '北海道',
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdateUrl(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=> $company->name,
            'prefecture' => $company->prefecture,
            'url' => 'http://test.com',
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => $company->prefecture,
            'url' => 'http://test.com',
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdateRemarks(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=> $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => 'テスト備考',
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => 'テスト備考',
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdateDeadline(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $deadline = new Carbon($company->deadline);
        $deadline_tommorow = $deadline->addYear();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=> $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $deadline_tommorow
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $deadline_tommorow,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdateExceptionPdf(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $deadline = new Carbon($company->deadline);
        $deadline_tommorow = $deadline->addYear();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=> 'テスト株式会社10',
            'prefecture' => '北海道',
            'url' => 'http://test.com',
            'remarks' => 'テスト備考',
            'deadline' => $deadline_tommorow
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name'=> 'テスト株式会社10',
            'prefecture' => '北海道',
            'url' => 'http://test.com',
            'remarks' => 'テスト備考',
            'deadline' => $deadline_tommorow,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdateByStudent()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this->actingAs($student)
            ->put(route('companies.update', $company->id), [
                'name'=>'テスト株式会社10',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testUpdateNoName()
    {
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=>'',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.edit', $company->id))
            // エラーメッセージがあること
            ->assertSessionHasErrors(['name' => '会社名は必須項目です。']);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdatePrefecture3over()
    {
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=>$company->name,
            'prefecture' => 'ロンドン',
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.edit', $company->id));
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdateByOtherTeacher(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', '<>', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=>'テスト株式会社10',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $company->create_user_id,
        ]);
    }

    public function testUpdateNotExistCompany(){
        // データが存在しない会社の更新処理のテスト
        $teacher = User::find(1);
        $company = Company::orderBy('id', 'desc')->first();
        $not_exist_company_id = $company->id + 1;
        $response = $this->actingAs($teacher)->put(route('companies.update', $not_exist_company_id), [
            'name'=>'テスト株式会社10',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', '会社データが存在しません');
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
