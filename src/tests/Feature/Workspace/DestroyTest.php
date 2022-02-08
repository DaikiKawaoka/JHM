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

class DestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDestroy()
    {
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->delete(route('workspaces.destroy', $workspace->id));
        $response->assertRedirect(route('progress.index'))
            ->assertStatus(302)
            ->assertSessionHas('status', 'ワークスペースの削除に成功しました');
    }

    public function testDestroyByOtherTeacher()
    {
        //教師が別の教師のワークスペースを削除する
        $teacher = User::find(1);
        $workspace = WorkSpaces::where('teacher_id', '<>' , $teacher->id)->first();
        $response = $this->actingAs($teacher)->delete(route('workspaces.destroy', $workspace->id));
        $response->assertRedirect(route('progress.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
        $this->assertDatabaseHas('workspaces', ['id' => $workspace->id]);
    }

    public function testDestroyByStudent()
    {
        //生徒がワークスペースを削除する
        $student = Students::first();
        $workspace = WorkSpaces::first();
        $response = $this->actingAs($student)
            ->delete(route('workspaces.destroy', $workspace->id));
        $response->assertRedirect(route('progress.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
        $this->assertDatabaseHas('workspaces', ['id' => $workspace->id]);
    }
}
