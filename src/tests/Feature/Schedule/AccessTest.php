<?php

namespace Tests\Feature\Schedule;

use App\Students;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AccessTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAccessCalendarByStudent()
    {
        $student = Students::first();
        $response = $this->actingAs($student)
            ->get('/schedule/calendar');
        $response->assertStatus(200);
    }

    public function testAccessCalendarByTeacher(){
        $teacher = User::first();
        $response = $this->actingAs($teacher)
            ->get('/schedule/calendar');
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
