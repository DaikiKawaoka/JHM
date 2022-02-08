<?php

namespace Tests\Feature\Workspace;

use App\Company;
use App\User;
use App\WorkSpaces;
use App\Membership;
use App\Students;
use Carbon\Carbon;
// use App\Rules\WorkSpaceYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;


class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWorkspcesUpdateName()
    {
        //ワークスペースの名前を変更
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('workspaces.update', $workspace->id),[
            'class_name' => 'ITエンジニア科4',
            'year' => $workspace->year
        ]);
        $response->assertRedirect(route('progress.index'))
                ->assertStatus(302)
                ->assertSessionHas('status', 'ワークスペースの情報を更新しました');
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'class_name' => 'ITエンジニア科4',
            'year' => $workspace->year
        ]);      
    }

    public function testWorkspcesUpdateYear()
    {
        //ワークスペースの年を変更
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('workspaces.update', $workspace->id),[
            'class_name' => $workspace->class_name,
            'year' => '2022'
        ]);
        $response->assertRedirect(route('progress.index'))
                ->assertStatus(302) 
                ->assertSessionHas('status', 'ワークスペースの情報を更新しました');
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'class_name' => $workspace->class_name,
            'year' => '2022'
        ]);        
    }

    public function testWorkspcesUpdateOverName31()
    {
        //ワークスペース名を31文字以上で変更するとき
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('workspaces.update', $workspace->id), [
            'class_name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'year' => $workspace->year
        ]);
        $response->assertStatus(302)
        ->assertRedirect(route('workspaces.edit', $workspace->id))
        //エラーメッセージ
        ->assertSessionHasErrors(['class_name' => '名前は31文字までです']);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'class_name' => $workspace->class_name
        ]);
    }

    public function testWorkspcesUpdateNoName()
    {
        //ワークスペースの名前を何も入力しなかった時
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('workspaces.update', $workspace->id), [
            'class_name' => '',
            'year' => $workspace->year
        ]);
        $response->assertStatus(302)
        ->assertRedirect(route('workspaces.edit', $workspace->id))
        //エラーメッセージ
        ->assertSessionHasErrors(['class_name' => '名前は必須項目です']);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'class_name' => $workspace->class_name
        ]);
    }
    
    public function testUpdateByOtherTeacher()
    {
        //教師が別の教師のワークスペースを編集するとき
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', '<>', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('workspaces.update', $workspace->id), [
            'class_name' => 'ゲームクリエイター3年生',
            'year' => $workspace->year
        ]);
        $response->assertRedirect(route('progress.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'class_name' => $workspace->class_name,
            'year' => $workspace->year
        ]);
    }

    public function testUpdateByStudent()
    {
        //生徒がワークスペースの編集をしようとする
        $student = Students::first();
        $workspace = WorkSpaces::first();
        $response = $this->actingAs($student)
            ->put(route('workspaces.update', $workspace->id), [
                'class_name' => 'ゲームクリエイター3年生',
                'year' => $workspace->year
            ]);
            $response->assertRedirect(route('progress.index'))
                ->assertStatus(302)
                ->assertSessionHas('status-error', 'アクセス権限がありません');
    }


    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
