<?php

namespace Tests\Feature\User;

use App\Students;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

class UpdateStudentTest extends TestCase
{

    public function test_dummy()
    {
        $this->markTestSkipped('ダミースキップ');
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    // public function updateStudentProfile(){
    //     $student = Students::first();
    //     // $response = $this
    //     //     ->actingAs($student)
    //     //     ->get('users/'.$student->id.'/edit');
    //     // $response -> assertStatus(200);
    //     // $response = $this
    //     //     ->put(route('users.updateStudentProfile', $student->id), ['attend_num'=>11, 'name'=>'田中一郎', 'email'=>'taro11@example.com']);
    //     // $response -> assertStatus(302);
    //     // $response -> assertSessionHas("status", "生徒（田中一郎）の情報を更新しました。");
    //     // $response -> assertRedirect('users/'.$student->id.'/edit');
    //     // $this->assertDatabaseHas('students', [
    //     //     'id' => $student->id,
    //     //     'name' => "田中一郎",
    //     //     'attend_num' => 11,
    //     //     'email' => "taro11@example.com",
    //     // ]);
    // }

    // /**
    //  * @test
    //  */
    // public function updateStudentPassword(){
    //     $student = Students::first();
    //     $response = $this
    //         ->actingAs($student)
    //         ->get('users/'.$student->id.'/edit');
    //     $response -> assertStatus(200);
    //     $test_password = 'success_test';
    //     $response = $this
    //         ->actingAs($student)
    //         ->put(route('users.updatePassword', $student->id), ['password_current'=>'password', 'password'=>$test_password, 'password_confirmation'=>$test_password]);
    //     $response -> assertStatus(302);
    //     $response -> assertSessionHas("status", "生徒（".$student->name."）のパスワードを更新しました。");
    //     $response -> assertRedirect('users/'.$student->id.'/edit');
    // }

    // /**
    //  * @test
    //  */
    // public function connectEditStudentProfileByTeacher(){
    //     $student = Students::first();
    //     $teacher = User::first();
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->get('users/'.$student->id.'/edit');
    //     $response->assertSessionHas("status-error", "自身のプロフィール以外編集できません。");
    //     $response->assertRedirect('/companies');
    // }

    // /**
    //  * @test
    //  */
    // public function updateStudentProfileByTeacher(){
    //     $student = Students::first();
    //     $teacher = User::first();
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->put(route('users.updateStudentProfile', $student->id), ['attend_num'=>11, 'name'=>'田中一郎', 'email'=>'taro11@example.com']);
    //     $response -> assertSessionHas("status-error", "更新対象が自身のプロフィールではないため、処理が失敗しました。");
    //     $response -> assertRedirect('/companies');
    // }

    // /**
    //  * @test
    //  */
    // public function updateStudentPasswordByTeacher(){
    //     $student = Students::first();
    //     $teacher = User::first();
    //     $test_password = 'failure_test';
    //     $response = $this
    //         ->actingAs($teacher)
    //         ->put(route('users.updatePassword', $student->id), ['password_current'=>'password', 'password'=>$test_password, 'password_confirmation'=>$test_password]);
    //     $response -> assertSessionHas("status-error", "更新対象が自身のパスワードではないため、処理が失敗しました。");
    //     $response -> assertRedirect('/companies');
    // }

    // /**
    //  * @test
    //  */
    // public function connectEditStudentProfileByOtherStudent(){
    //     $student = Students::first();
    //     $other_student = Students::where('id', '<>', $student->id)->first();
    //     $response = $this
    //         ->actingAs($other_student)
    //         ->get('users/'.$student->id.'/edit');
    //     $response->assertSessionHas("status-error", "自身のプロフィール以外編集できません。");
    //     $response->assertRedirect('/companies');
    // }

    // /**
    //  * @test
    //  */
    // public function updateStudentProfileByOtherStudent(){
    //     $student = Students::first();
    //     $other_student = Students::where('id', '<>', $student->id)->first();
    //     $response = $this
    //         ->actingAs($other_student)
    //         ->put(route('users.updateStudentProfile', $student->id), ['attend_num'=>11, 'name'=>'田中一郎', 'email'=>'taro11@example.com']);
    //     $response -> assertSessionHas("status-error", "更新対象が自身のプロフィールではないため、処理が失敗しました。");
    //     $response -> assertRedirect('/companies');
    // }

    // /**
    //  * @test
    //  */
    // public function updateStudentPasswordByOtherStudent(){
    //     $student = Students::first();
    //     $other_student = Students::where('id', '<>', $student->id)->first();
    //     $test_password = 'failure_test';
    //     $response = $this
    //         ->actingAs($other_student)
    //         ->put(route('users.updatePassword', $student->id), ['password_current'=>'password', 'password'=>$test_password, 'password_confirmation'=>$test_password]);
    //     $response -> assertSessionHas("status-error", "更新対象が自身のパスワードではないため、処理が失敗しました。");
    //     $response -> assertRedirect('/companies');
    // }

}
