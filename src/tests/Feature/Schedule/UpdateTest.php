<?php

namespace Tests\Feature\Schedule;

use App\Schedules;
use App\Students;
use App\User;
use App\WorkSpaces;
use Carbon\Carbon;
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
    public function testUpdateContent()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $teacher = User::find($workspace->teacher_id);
        $response = $this->actingAs($teacher)
            ->withCookie('workspace_id', $workspace->id)
            ->post(route('schedule.update', $schedule->id), [
                'content' => 'テスト株式会社集団面接',
                'schedule_date' => $schedule->schedule_date,
            ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status', '予定を更新しました');
    }

    public function testUpdateScheduleDate()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $teacher = User::find($workspace->teacher_id);
        $schedule_date = new Carbon($schedule->schedule_date);
        $schedule_date_afterYear = $schedule_date->addYear();
        $response = $this->actingAs($teacher)
            ->withCookie('workspace_id', $workspace->id)
            ->post(route('schedule.update', $schedule->id), [
                'content' => $schedule->content,
                'schedule_date' => $schedule_date_afterYear,
            ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status', '予定を更新しました');
    }

    public function testUpdateTwo()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $teacher = User::find($workspace->teacher_id);
        $schedule_date = new Carbon($schedule->schedule_date);
        $schedule_date_afterYear = $schedule_date->addYear();
        $response = $this->actingAs($teacher)
            ->withCookie('workspace_id', $workspace->id)
            ->post(route('schedule.update', $schedule->id), [
                'content' => 'テスト株式会社個人面接',
                'schedule_date' => $schedule_date_afterYear,
            ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status', '予定を更新しました');
    }

    public function testUpdateByOtherTeacher()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $teacher = User::where('id', '<>', $workspace->teacher_id)->first();
        $response = $this->actingAs($teacher)
            ->withCookie('workspace_id', $workspace->id)
            ->post(route('schedule.update', $schedule->id), [
                'content' => 'テスト株式会社集団面接',
                'schedule_date' => $schedule->schedule_date,
            ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testUpdateByStudent()
    {
        $schedule = Schedules::first();
        $workspace = WorkSpaces::find($schedule->workspace_id);
        $student = Students::first();
        $response = $this->actingAs($student)
            ->withCookie('workspace_id', $workspace->id)
            ->post(route('schedule.update', $schedule->id), [
                'content' => 'テスト株式会社集団面接',
                'schedule_date' => $schedule->schedule_date,
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
