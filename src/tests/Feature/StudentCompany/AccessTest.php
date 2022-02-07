<?php

namespace Tests\Feature\StudentCompany;

use App\StudentCompany;
use App\Students;
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
    public function testAccessCreate()
    {
        $student = Students::first();
        $response = $this->actingAs($student)->get(route('studentCompanies.create'));
        $response->assertStatus(200);
    }

    public function testAccessShow(){
        $student_company = StudentCompany::first();
        $student = Students::find($student_company->create_student_id);
        $response = $this->actingAs($student)->get(route('studentCompanies.show', $student_company->id));
        $response->assertStatus(200);
    }

    public function testAccessEdit(){
        $student_company = StudentCompany::first();
        $student = Students::find($student_company->create_student_id);
        $response = $this->actingAs($student)->get(route('studentCompanies.edit', $student_company->id));
        $response->assertStatus(200);
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
