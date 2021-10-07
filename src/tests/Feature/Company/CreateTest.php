<?php

namespace Tests\Feature\Company;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStudentCreatesCompany()
    {
        $student = User::where('is_teacher',0)->first();
        $response = $this
            ->actingAs($student)
            ->get('companies/create');
        $response->assertStatus(200);

        $today = Carbon::tomorrow('Asia/Tokyo');
        $response = $this->post(route('companies.store'), ['name' => '株式会社テスト','prefecture' => '愛媛', 'url' => 'https://test.com', 'remarks' => 'なし','deadlind' => $today]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies');

        $this->assertDatabaseHas('companies', [
            'name' => '株式会社テスト',
            'create_user_id' => $student->id,
        ]);
    }

    public function testTeacherCreatesCompany()
    {
        $teacher = User::where('is_teacher',1)->first();
        $response = $this
            ->actingAs($teacher)
            ->get('companies/create');
        $response->assertStatus(200);

        $today = Carbon::tomorrow('Asia/Tokyo');
        $response = $this->post(route('companies.store'), ['name' => '株式会社テスト','prefecture' => '愛媛', 'url' => 'https://test.com', 'remarks' => 'なし','deadlind' => $today]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies');

        $this->assertDatabaseHas('companies', [
            'name' => '株式会社テスト',
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testCompanyCreateNoNameAndDeadline()
    {
        $user = User::first();
        $response = $this
            ->actingAs($user)
            ->get('companies/create');
        $response->assertStatus(200);

        $yesterday = Carbon::yesterday();
        $response = $this->post(route('companies.store'), ['name' => '','prefecture' => '愛媛', 'url' => 'https://test.com', 'remarks' => 'なし','deadline' => $yesterday]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies/create');

        // エラーメッセージがあること
        $response->assertSessionHasErrors(['name' => '会社名は必須項目です。','deadline' => '締切日は本日以降にしてください。']);
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
