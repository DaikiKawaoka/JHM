<?php

namespace Tests\Feature\Company;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpdateName()
    {
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=>'テスト株式会社10', 'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'テスト株式会社10',
            'prefecture' => $company->prefecture,
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }

    public function testUpdatePrefecture(){
        $teacher = User::find(1);
        $company = Company::where('create_user_id', $teacher->id)->first();
        $response = $this->actingAs($teacher)->put(route('companies.update', $company->id), [
            'name'=> $company->name,
            'prefecture' => '北海道',
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline
        ]);
        $response->assertRedirect(route('companies.show', $company->id))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を更新しました');
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'prefecture' => '北海道',
            'url' => $company->url,
            'remarks' => $company->remarks,
            'deadline' => $company->deadline,
            'create_user_id' => $teacher->id,
        ]);
    }
}
