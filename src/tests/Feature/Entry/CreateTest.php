<?php

namespace Tests\Feature\Entry;

use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_dummy()
    {
        $this->markTestSkipped('ダミースキップ');
        $this->assertTrue(true);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testNonTeacherEntersCompany()
    // {
    //     $non_teacher = Students::first();
    //     $response = $this
    //         ->actingAs($non_teacher)
    //         ->get('companies');
    //     $response->assertStatus(200);

    //     $response = $this->post(route('entries.store'), ['student_id' => $non_teacher->id,'company_id' => 2]);
    //     // リダイレクトでページ遷移してくるのでstatusは302
    //     $response->assertStatus(302);
    //     $response->assertRedirect('/companies/2');

    //     $this->assertDatabaseHas('entries', [
    //         'student_id' => $non_teacher->id,
    //         'company_id' => 2,
    //     ]);
    //     // メッセージがあること
    //     $response->assertSessionHas("status", "test2株式会社にエントリーしました。");
    // }

    // public function testTeacherEntersCompany()
    // {
    //     $teacher = User::first();
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('companies');
    //     $response->assertStatus(200);

    //     $response = $this->post(route('entries.store'), ['student_id' => $teacher->id,'company_id' => 2]);
    //     // リダイレクトでページ遷移してくるのでstatusは302
    //     $response->assertStatus(302);
    //     $response->assertRedirect('/companies');

    //     // メッセージがあること
    //     $response->assertSessionHas("status-error", "あなたは教師なのでエントリーできません。");
    // }

    // public function testEnterCompanyAlreadyEntered()
    // {
    //     $non_teacher = Students::first();
    //     $response = $this
    //         ->actingAs($non_teacher)
    //         ->get('companies');
    //     $response->assertStatus(200);

    //     // 1度目のエントリー
    //     $response = $this->post(route('entries.store'), ['student_id' => $non_teacher->id,'company_id' => 2]);
    //     $this->assertDatabaseHas('entries', [
    //         'student_id' => $non_teacher->id,
    //         'company_id' => 2,
    //     ]);
    //     $response->assertSessionHas("status", "test2株式会社にエントリーしました。");

    //     // ２度目のエントリー
    //     $response = $this->post(route('entries.store'), ['student_id' => $non_teacher->id,'company_id' => 2]);
    //     $response->assertSessionHas("status-error", "過去にあなたはtest2株式会社にエントリー済みです。");
    // }


    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
