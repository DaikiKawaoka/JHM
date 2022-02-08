<?php

namespace Tests\Feature\Schedule;

use App\Schedules;
use App\Students;
use App\User;
use App\WorkSpaces;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDestroy()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $teacher = User::find($workspace->teacher_id);
        $response = $this->actingAs($teacher)
            ->withCookie('workspace_id', $workspace->id)
            ->delete(route('schedule.destroy', $schedule->id));
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status', '予定を削除しました');
    }

    public function testDestroyByOtherTeacher()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $teacher = User::where('id', '<>', $workspace->teacher_id)->first();
        $response = $this->actingAs($teacher)
            ->withCookie('workspace_id', $workspace->id)
            ->delete(route('schedule.destroy', $schedule->id));
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testDestroyByStudent()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $student = Students::first();
        $response = $this->actingAs($student)
            ->withCookie('workspace_id', $workspace->id)
            ->delete(route('schedule.destroy', $schedule->id));
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
