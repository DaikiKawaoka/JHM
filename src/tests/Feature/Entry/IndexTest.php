<?php

namespace Tests\Feature\Entry;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\User;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    public function testEntryIndexPage()
    {
        $non_teacher = User::where('is_teacher',0)->first();
        $response = $this
            ->actingAs($non_teacher)
            ->get('entries');
        $response->assertStatus(200);
        $response->assertSeeText("test1株式会社")
                ->assertSeeText("愛媛");
    }

    public function testEntryIndexPageByTeacher()
    {
        $teacher = User::where('is_teacher',1)->first();
        $response = $this
            ->actingAs($teacher)
            ->get('entries');
        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
