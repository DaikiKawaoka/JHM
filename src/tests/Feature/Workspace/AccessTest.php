<?php

namespace Tests\Feature\Workspace;

use App\Company;
use App\User;
use App\WorkSpaces;
use App\Membership;
use App\Students;
use Carbon\Carbon;
use App\Rules\WorkSpaceYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;


class AccessTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAccessCreateByStudent()
    {
        //生徒がワークスペースの作成ページにアクセスする
        $student = Students::first();
        $response = $this->actingAs($student)->get('workspaces/create');
        $response->assertStatus(302)
                ->assertRedirect('/companies')
                ->assertSessionHas("status-error", "アクセス権限がありません");
    }

    public function testAccessEditByStudent()
    {
        //生徒がワークスペースの編集ページにアクセスする
        $student = Students::first();
        $workspace = WorkSpaces::first();
        $response = $this->actingAs($student)->get('/workspaces/'.$workspace->id.'/edit');
        $response->assertStatus(302)
                ->assertRedirect('/companies')
                ->assertSessionHas("status-error", "アクセス権限がありません");
    }

    public function testAccessCreateByTeacher()
    {
        //教師がワークスペースの作成ページにアクセスする
        $teacher = User::first();
        $response = $this->actingAs($teacher)->get('/workspaces/create');
        $response->assertStatus(200);
    }

    public function testAccessMyEditByTeacher()
    {
        //教師が自分のワークスペース編集ページにアクセスする
        $teacher = User::first();
        $workspace = WorkSpaces::where('teacher_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->get('/workspaces/'.$workspace->id.'/edit');
        $response->assertStatus(200);
    }   
    
    public function testAccessOtherEditByTeacher()
    {
        //教師が別の教師のワークスペースの編集ページにアクセスする
        $teacher = User::first();
        $workspace = WorkSpaces::where('teacher_id', '<>' ,$teacher->id)->first();
        $response = $this->actingAs($teacher)->get('/workspaces/'.$workspace->id.'/edit');
        $response->assertStatus(302)
                ->assertRedirect('/companies')
                ->assertSessionHas("status-error", "アクセス権限がありません");

    }
}
