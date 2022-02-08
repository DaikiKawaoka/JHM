<?php

namespace Tests\Feature\StudentCompany;

use App\StudentCompany;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateCompanyMin()
    {
        $student = Students::first();

        $response = $this->actingAs($student)->post(route('studentCompanies.store'), ['name' => '株式会社テスト','prefecture' => '']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $student_company = StudentCompany::where('name', '株式会社テスト')->where('create_student_id', $student->id)->first();
        $response->assertStatus(302)
            ->assertRedirect(route('studentCompanies.show',$student_company->id))
            ->assertSessionHas("status", "会社情報を登録しました");

        $this->assertDatabaseHas('student_companies', [
            'name' => '株式会社テスト',
            'create_student_id' => $student->id,
        ]);
    }

    public function testCreateCompanyFull()
    {
        $student = Students::first();
        $response = $this->actingAs($student);

        $response = $this->post(route('studentCompanies.store'),[
            'name' => '株式会社テスト',
            'prefecture' => '愛媛'
        ]);
        $student_company = StudentCompany::where('name', '株式会社テスト')->where('create_student_id', $student->id)->first();
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302)
            ->assertRedirect(route('studentCompanies.show', $student_company->id))
            ->assertSessionHas("status", "会社情報を登録しました");

        $this->assertDatabaseHas('student_companies', [
            'name' => '株式会社テスト',
            'prefecture' => '愛媛',
            'create_student_id' => $student->id
        ]);
    }

    public function testCreateCompanyNoName()
    {
        $student = Students::first();
        $response = $this->actingAs($student)->post(route('studentCompanies.store'), ['name' => '','prefecture' => '愛媛']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302)
            ->assertRedirect(route('studentCompanies.create'))
            ->assertSessionHasErrors(['name' => '会社名は必須項目です']);
    }

    public function testCreateCompanyByTeacher(){
        $teacher = User::first();
        $response = $this
            ->actingAs($teacher)
            ->post(route('studentCompanies.store'), ['name' => '株式会社テスト','prefecture' => '']);
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
