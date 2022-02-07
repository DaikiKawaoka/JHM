<?php

namespace Tests\Feature\Company;

use App\Company;
use App\Students;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class AccessTest extends TestCase
{

    use RefreshDatabase;

    //会社関連ページ：一覧、追加、詳細、編集

    public function testAccessIndexByStudent()
    {
        //生徒アカウントで会社一覧ページにアクセスする
        $student = Students::first();
        $response = $this->actingAs($student)->get('/companies');
        $response->assertStatus(200);
    }

    public function testAccessShowByStudent()
    {
        //生徒アカウントで会社の詳細ページにアクセスする
        $student = Students::first();
        $response = $this->actingAs($student)->get('/companies/1');
        $response->assertStatus(200);
    }

    public function testAccessCreateByStudent()
    {
        //生徒アカウントで会社の登録ページにアクセスする
        $student = Students::first();
        $response = $this->actingAs($student)->get('/companies/create');
        $response->assertStatus(302)
                    ->assertRedirect('/companies')
                    ->assertSessionHas("status-error", "アクセス権限がありません");
    }

    public function testAccessEditPageByStudent()
    {
        //生徒アカウントで会社の編集ページにアクセスする
        $student = Students::first();
        $company = Company::first();
        $response = $this->actingAs($student)->get('/companies/'.$company->id.'/edit');
        $response->assertStatus(302)
                    ->assertRedirect('/companies')
                    ->assertSessionHas("status-error", "アクセス権限がありません");
    }

    public function testAccessIndexByTeacher()
    {
        //教師アカウントで会社一覧ページにアクセスする
        $teacher = User::first();
        $response = $this->actingAs($teacher)->get('/companies');
        $response->assertStatus(200);
    }

    public function testAccessShowByTeacher()
    {
        //教師アカウントで会社詳細ページにアクセスする
        $teacher = User::first();
        $response = $this->actingAs($teacher)->get('/companies/2');
        $response->assertStatus(200);
    }

    public function testAccessCreateByTeacher()
    {
        //教師アカウントで会社登録ページにアクセスする
        $teacher = User::first();
        $response = $this->actingAs($teacher)->get('/companies/create');
        $response->assertStatus(200);
    }

    public function testAccessMyEditPageByTeacher()
    {
        //教師アカウントで自身が登録した会社の編集ページにアクセスする
        $teacher = User::first();
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->get('/companies/'.$company->id.'/edit');
        $response->assertStatus(200);
    }

    public function testAccessOtherEditPageByTeacher()
    {
        //教師アカウントで別ユーザが登録した会社の編集ページにアクセスする
        $teacher = User::first();
        $company = Company::where('create_user_id', '<>', $teacher->id)->first();
        $response = $this->actingAs($teacher)->get('/companies/'.$company->id.'/edit');
        $response->assertStatus(302)
                    ->assertRedirect('/companies')
                    ->assertSessionHas("status-error", "アクセス権限がありません");
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
