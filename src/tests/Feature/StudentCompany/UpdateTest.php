<?php

namespace Tests\Feature\StudentCompany;

use App\StudentCompany;
use App\Students;
use App\User;
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
        $student_company = StudentCompany::first();
        $student_id = $student_company->create_student_id;
        $student = Students::find($student_id);
        $response = $this->actingAs($student)->put(route('studentCompanies.update', $student_company->id), [
            'name' => 'テスト株式会社11',
            'prefecture' => $student_company->prefecture
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('studentCompanies.show', $student_company->id))
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('student_companies', [
            'id' => $student_company->id,
            'name' => 'テスト株式会社11',
            'prefecture' => $student_company->prefecture,
            'create_student_id' => $student_id
        ]);
    }

    public function testUpdatePrefecture()
    {
        $student_company = StudentCompany::first();
        $student_id = $student_company->create_student_id;
        $student = Students::find($student_id);
        $response = $this->actingAs($student)->put(route('studentCompanies.update', $student_company->id), [
            'name' => $student_company->name,
            'prefecture' => '北海道'
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('studentCompanies.show', $student_company->id))
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('student_companies', [
            'id' => $student_company->id,
            'name' => $student_company->name,
            'prefecture' => '北海道',
            'create_student_id' => $student_id
        ]);
    }

    public function testUpdateNoName()
    {
        $student_company = StudentCompany::first();
        $student_id = $student_company->create_student_id;
        $student = Students::find($student_id);
        $response = $this->actingAs($student)->put(route('studentCompanies.update', $student_company->id), [
            'name' => '',
            'prefecture' => $student_company->prefecture
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('studentCompanies.edit', $student_company->id))
            ->assertSessionHasErrors(['name' => '会社名は必須項目です']);
        $this->assertDatabaseHas('student_companies', [
            'id' => $student_company->id,
            'name' => $student_company->name,
            'prefecture' => $student_company->prefecture,
            'create_student_id' => $student_id
        ]);
    }

    public function testUpdateNotExistCompany(){
        $student_company_id = StudentCompany::orderBy('id', 'desc')->first()->id + 1;
        $student = Students::first();
        $response = $this->actingAs($student)->put(route('studentCompanies.update', $student_company_id), [
            'name' => 'テスト株式会社11',
            'prefecture' => '東京'
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', '会社データが存在しません');
    }

    public function testUpdateByTeacher(){
        $student_company = StudentCompany::first();
        $teacher = User::first();
        $response = $this->actingAs($teacher)->put(route('studentCompanies.update', $student_company->id), [
            'name' => 'テスト株式会社11',
            'prefecture' => '東京'
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testUpdateByOtherStudent(){
        $student_company = StudentCompany::first();
        $student = Students::where('id', '<>', $student_company->create_user_id)->first();
        $response = $this->actingAs($student)->put(route('studentCompanies.update', $student_company->id), [
            'name' => 'テスト株式会社11',
            'prefecture' => '東京'
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
