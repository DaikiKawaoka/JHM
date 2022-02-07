<?php

namespace Tests\Feature\Company;

use App\Company;
use App\Students;
use App\User;
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
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)
            ->delete(route('companies.destroy', $company->id));
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を削除しました');
        $this->assertSoftDeleted('companies', ['id' => $company->id]);
    }

    public function testDestroyByOtherTeacher()
    {
        $teacher = User::find(1);
        $company = Company::where('create_user_id', '<>', $teacher->id)->first();
        $response = $this->actingAs($teacher)
            ->delete(route('companies.destroy', $company->id));
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    public function testDestroyByStudent()
    {
        $student = Students::first();
        $company = Company::first();
        $response = $this->actingAs($student)
            ->delete(route('companies.destroy', $company->id));
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status-error', 'アクセス権限がありません');
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
