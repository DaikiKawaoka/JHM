<?php

namespace Tests\Feature\Company;

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
        $teacher = User::first();

        $response = $this->actingAs($teacher)->post(route('companies.store'), ['name' => '株式会社テスト','prefecture' => '', 'url' => '', 'remarks' => '','deadlind' => '']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302)
            ->assertRedirect('/companies')
            ->assertSessionHas("status", "求人情報を追加しました");

        $this->assertDatabaseHas('companies', [
            'name' => '株式会社テスト',
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testCreateCompanyFull()
    {
        $this->markTestIncomplete('このテストは、まだ実装されていません。（PDF）');
        Storage::fake('public');
        $teacher = User::first();
        $response = $this->actingAs($teacher);

        $today = Carbon::tomorrow('Asia/Tokyo');
        $pdf = UploadedFile::fake()->create('test.pdf', 1, 'public/pdf');
        $response = $this->post(route('companies.store'),[
            'name' => '株式会社テスト',
            'prefecture' => '愛媛',
            'url' => 'https://test.com',
            'remarks' => 'なし',
            'deadline' => $today,
            'pdf1' => $pdf
        ]);

        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302)
            ->assertRedirect('/companies')
            ->assertSessionHas("status", "求人情報を追加しました");

        $this->assertDatabaseHas('companies', [
            'name' => '株式会社テスト',
            'prefecture' => '愛媛',
            'url' => 'https://test.com',
            'remarks' => 'なし',
            'deadline' => $today,
            'create_user_id' => $teacher->id,
            'image_path' => 'test株式会社',
        ]);
    }

    public function testCreateCompanyNoName()
    {
        $teacher = User::first();
        $response = $this->actingAs($teacher)->post(route('companies.store'), ['name' => '','prefecture' => '愛媛', 'url' => 'https://test.com', 'remarks' => 'なし','deadline' => '']);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies/create');

        // エラーメッセージがあること
        $response->assertSessionHasErrors(['name' => '会社名は必須項目です。']);
    }

    public function testCreateCompanyDeadlineYesterday(){
        $teacher = User::first();
        $yesterday = Carbon::yesterday();
        $response = $this->actingAs($teacher)->post(route('companies.store'), ['name' => '株式会社テスト','prefecture' => '愛媛', 'url' => 'https://test.com', 'remarks' => 'なし','deadline' => $yesterday]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies/create');

        // エラーメッセージがあること
        $response->assertSessionHasErrors(['deadline' => '締切日は本日以降にしてください。']);
    }

    public function testCreateCompanyByStudent(){
        $student = Students::first();
        $response = $this
            ->actingAs($student)
            ->post(route('companies.store'), ['name' => '株式会社テスト','prefecture' => '', 'url' => '', 'remarks' => '','deadlind' => '']);
        $response->assertStatus(302)
            ->assertRedirect('/companies')
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
