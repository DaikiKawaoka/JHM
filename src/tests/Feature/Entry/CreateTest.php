<?php

namespace Tests\Feature\Entry;

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
    public function testNonTeacherEntersCompany()
    {
        $non_teacher = User::where('is_teacher',0)->first();
        $response = $this
            ->actingAs($non_teacher)
            ->get('companies');
        $response->assertStatus(200);

        $response = $this->post(route('entries.store'), ['user_id' => $non_teacher->id,'company_id' => 2]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies');

        $this->assertDatabaseHas('entries', [
            'user_id' => $non_teacher->id,
            'company_id' => 2,
        ]);
        // メッセージがあること
        $response->assertSessionHas("status", "test2株式会社にエントリーしました。");
    }

    public function testTeacherEntersCompany()
    {
        $teacher = User::where('is_teacher',1)->first();
        $response = $this
            ->actingAs($teacher)
            ->get('companies');
        $response->assertStatus(200);

        $response = $this->post(route('entries.store'), ['user_id' => $teacher->id,'company_id' => 2]);
        // リダイレクトでページ遷移してくるのでstatusは302
        $response->assertStatus(302);
        $response->assertRedirect('/companies');

        // メッセージがあること
        $response->assertSessionHas("status-error", "あなたは教師なのでエントリーできません。");
    }

    public function testEnterCompanyAlreadyEntered()
    {
        $non_teacher = User::where('is_teacher',0)->first();
        $response = $this
            ->actingAs($non_teacher)
            ->get('companies');
        $response->assertStatus(200);

        // 1度目のエントリー
        $response = $this->post(route('entries.store'), ['user_id' => $non_teacher->id,'company_id' => 2]);
        $this->assertDatabaseHas('entries', [
            'user_id' => $non_teacher->id,
            'company_id' => 2,
        ]);
        $response->assertSessionHas("status", "test2株式会社にエントリーしました。");

        // ２度目のエントリー
        $response = $this->post(route('entries.store'), ['user_id' => $non_teacher->id,'company_id' => 2]);
        $response->assertSessionHas("status-error", "過去にあなたはtest2株式会社にエントリー済みです。");
    }


    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
