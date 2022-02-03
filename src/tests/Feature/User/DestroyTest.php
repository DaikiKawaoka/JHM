<?php

namespace Tests\Feature\User;

use App\Membership;
use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\User;
use App\WorkSpaces;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_dummy()
    {
        $this->markTestSkipped('ダミースキップ');
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    // public function TeacherDeletesStudent()
    // {
    //     $teacher = User::first();
    //     $student = Students::first();
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->assertAuthenticatedAs($teacher);

    //     $response = $this->delete(route('users.destroy',['user' => $student->id]));
    //     $response->assertStatus(302);
    //     $response->assertSessionHas("status", "生徒（" . $student->name . "）を削除しました。");

    //     $this->assertSoftDeleted('users', [
    //         'id' => $student->id,
    //         'email' => $student->email,
    //     ]);
    // }

    // /**
    //  * @test
    //  */
    // public function TeacherDeletesUserNotHisStudent()
    // {
    //     // $teacher = User::first();
    //     // $workspace = WorkSpaces::whereNot('teacher_id', $teacher->id)->first();
    //     // $student_id = Membership::where('workspace_id', $workspace->id)->first()->student_id;
    //     // $student = Students::find($student_id);
    //     // $response = $this
    //     //     ->actingAs($teacher)
    //     //     ->assertAuthenticatedAs($teacher);

    //     // $response = $this->delete(route('users.destroy',['user' => $student->id]));
    //     // $response->assertStatus(302);
    //     // $response->assertSessionHas("status-error", "削除対象の生徒が自分の生徒ではない為、処理が失敗しました。");

    //     // $this->assertDatabaseHas('users', [
    //     //     'id' => $student->id,
    //     //     'deleted_at' => NULL,
    //     // ]);
    // }
    // /**
    //  * @test
    //  */
    // public function TeacherDeletesNotExsistsStudent()
    // {
    //     $teacher = User::first();
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->assertAuthenticatedAs($teacher);
    //     // 存在しないユーザID（999999）の生徒を削除
    //     $response = $this->delete(route('users.destroy',['user' => 999999]));
    //     $response->assertStatus(302);
    //     $response->assertSessionHas("status-error", "削除対象の生徒が存在しない為、処理が失敗しました。");
    // }

    // /**
    //  * @test
    //  */
    // public function StudentDeletesStudent()
    // {
    //     $student = Students::first();
    //     $student_to_be_deleted = Students::where('id','!=',$student->id)->first();
    //     $response = $this
    //         ->actingAs($student)
    //         ->assertAuthenticatedAs($student);
    //     // 生徒が生徒を削除(失敗)
    //     $response = $this->delete(route('users.destroy',['user' => $student_to_be_deleted->id]));
    //     $response->assertStatus(302)
    //             ->assertRedirect('home');

    //     $this->assertDatabaseHas('users', [
    //         'id' => $student_to_be_deleted->id,
    //         'deleted_at' => NULL,
    //     ]);
    // }

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     Artisan::call('migrate:refresh');
    //     Artisan::call('db:seed');
    // }
}
