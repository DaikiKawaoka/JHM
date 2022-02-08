<?php

namespace Tests\Feature\Workspace;

use App\Company;
use App\User;
use App\WorkSpaces;
use App\Membership;
use Carbon\Carbon;
use App\Rules\WorkSpaceYear;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;


class CreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateWorkspaces()
    {
        $teacher = User::first();
        $now = Carbon::now();
        $this_year = $now->year;
        $response = $this->actingAs($teacher)->post(route('workspaces.store'), [
            'class_name' => 'ITエンジニア科4年制', 
            'year' => $this_year
        ]);
        $workspace = WorkSpaces::where('class_name','ITエンジニア科4年制')->where('year',$this_year)->first();

        $response->assertStatus(302)
                ->assertRedirect(route('workspaces.createStudentsShow'));
        
        // $res = $this->post(route('workspaces.addStudents',['workspace_id' => $workspace->id]), [
        //             'students[]' => 1
        //         ]);
        // // $res->assertStatus(302)
        //     ->assertRedirect(route('workspace.showMember'));


        $this->assertDatabaseHas('workspaces',[
            'class_name' => 'ITエンジニア科4年制',
            'year' => $this_year,
        ]);

    }

    public function testCreateWorkspacesMaxNameOver31()
    {
        //31文字以上でワークスペースを作成する時
        $teacher = User::first();
        $now = Carbon::now();
        $this_year = $now->year;
        $response = $this->actingAs($teacher)->post(route('workspaces.store'), ['class_name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'year' => $this_year]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302)
                 ->assertRedirect(route('workspaces.create'));
        //エラーメッセージ
        $response->assertSessionHasErrors(['class_name' => '名前は31文字までです']);

    }

    public function testCreateWorkspacesByStudent()
    {
        //生徒がワークスペースを作成するとき
        $student = Students::first();
        $now = Carbon::now();
        $this_year = $now->year;
        $response = $this->actingAs($student)
            ->post(route('workspaces.store'), [
                'class_name' => 'ITエンジニア科4年制', 
                'year' => $this_year
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
