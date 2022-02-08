<?php

namespace Tests\Feature\Schedule;

use App\Students;
use App\User;
use App\WorkSpaces;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $workspace = WorkSpaces::first();
        $teacher = User::find($workspace->teacher_id);
        $schedule_date = Carbon::tomorrow('Asia/Tokyo');
        $response = $this->withCookie('workspace_id', $workspace->id)
            ->actingAs($teacher)
            ->post(route('schedule.store'), [
                'content' => 'テスト株式会社説明会',
                'schedule_date' => $schedule_date,
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status', '予定を新規作成しました');

    }

    public function testCreateByOtherTeacher()
    {
        $workspace = WorkSpaces::first();
        $teacher = User::where('id', '<>', $workspace->teacher_id)->first();
        $schedule_date = Carbon::tomorrow('Asia/Tokyo');
        $response = $this->withCookie('workspace_id', $workspace->id)
            ->actingAs($teacher)
            ->post(route('schedule.store'), [
                'content' => 'テスト株式会社説明会',
                'schedule_date' => $schedule_date,
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testCreateByStudent()
    {
        $workspace = WorkSpaces::first();
        $student = Students::first();
        $schedule_date = Carbon::tomorrow('Asia/Tokyo');
        $response = $this->withCookie('workspace_id', $workspace->id)
            ->actingAs($student)
            ->post(route('schedule.store'), [
                'content' => 'テスト株式会社説明会',
                'schedule_date' => $schedule_date,
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testCreateNoContent()
    {
        $workspace = WorkSpaces::first();
        $teacher = User::find($workspace->teacher_id);
        $schedule_date = Carbon::tomorrow('Asia/Tokyo');
        $response = $this->withCookie('workspace_id', $workspace->id)
            ->actingAs($teacher)
            ->post(route('schedule.store'), [
                'content' => '',
                'schedule_date' => $schedule_date,
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status-error', '予定日は本日以降、予定内容は1~31文字以内です');

    }

    public function testCreateNoDate()
    {
        $workspace = WorkSpaces::first();
        $teacher = User::find($workspace->teacher_id);
        $response = $this->withCookie('workspace_id', $workspace->id)
            ->actingAs($teacher)
            ->post(route('schedule.store'), [
                'content' => 'テスト株式会社説明会',
                'schedule_date' => '',
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status-error', '予定日は本日以降、予定内容は1~31文字以内です');

    }

    public function testCreateYesterdayDate()
    {
        $workspace = WorkSpaces::first();
        $teacher = User::find($workspace->teacher_id);
        $schedule_date = Carbon::yesterday('Asia/Tokyo');
        $response = $this->withCookie('workspace_id', $workspace->id)
            ->actingAs($teacher)
            ->post(route('schedule.store'), [
                'content' => 'テスト株式会社説明会',
                'schedule_date' => $schedule_date,
        ]);
        $response->assertStatus(302)
            ->assertRedirect(route('workspaces.calendar'))
            ->assertSessionHas('status-error', '予定日は本日以降、予定内容は1~31文字以内です');

    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
