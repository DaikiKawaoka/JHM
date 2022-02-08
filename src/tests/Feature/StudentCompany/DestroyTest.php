<?php

namespace Tests\Feature\StudentCompany;

use App\StudentCompany;
use App\Students;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeatroyCompany()
    {
        $student_company = StudentCompany::first();
        $student = Students::find($student_company->create_student_id);
        $response = $this->actingAs($student)
            ->delete(route('studentCompanies.destroy', $student_company->id));
        $response->assertRedirect(route('companies.index'))
            ->assertStatus(302)
            ->assertSessionHas('status', '会社情報を削除しました');
        $this->assertDatabaseMissing('student_companies', [
            'id' => $student_company->id
        ]);
    }

    public function testDeatroyCompanyByOtherStudent(){
        $student_company = StudentCompany::first();
        $student = Students::where('id', '<>', $student_company->create_student_id)->first();
        $response = $this->actingAs($student)
            ->delete(route('studentCompanies.destroy', $student_company->id));
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }

    public function testDeatroyCompanyByTeacher(){
        $student_company = StudentCompany::first();
        $teacher = User::first();
        $response = $this->actingAs($teacher)
            ->delete(route('studentCompanies.destroy', $student_company->id));
        $response->assertStatus(302)
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status-error', 'アクセス権限がありません');
    }
}
