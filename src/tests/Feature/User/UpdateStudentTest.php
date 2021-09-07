<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UpdateStudentTest extends TestCase
{
    /**
     * @test
     */
    public function updateStudent(){
        $student = User::where('is_teacher',0)->first();
        $response = $this
            ->actingAs($student)
            ->get('users/'.$student->id.'/edit');
        $response -> assertStatus(200);
        $response = $this
            ->put(route('users.updateStudentProfile', $student->id), ['attend_num'=>11, 'name'=>'田中一郎', 'email'=>'taro11@example.com']);
        $response -> assertStatus(302);
        $response -> assertSessionHas("status", "生徒（田中一郎）の情報を更新しました。");
        $response -> assertRedirect('users/'.$student->id.'/edit');
        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'name' => "田中一郎",
            'attend_num' => 11,
            'email' => "taro11@example.com",
        ]);
    }

    public function connectEditStudentProfileByTeacher(){
        $student = User::where('is_teacher',0)->first();
        $teacher = User::where('is_teacher',1)->first();
        $response = $this
            ->actingAs($teacher)
            ->get('users/'.$student->id.'/edit');
        $response->assertSessionHas("status-error", "自身のプロフィール以外編集できません。");
        $response->assertRedirect('/companies');
    }

    public function updateStudentProfileByTeacher(){
        $student = User::where('is_teacher',0)->first();
        $teacher = User::where('is_teacher',1)->first();
        $response = $this
            ->actingAs($teacher)
            ->put(route('users.updateStudentProfile', $student->id), ['attend_num'=>11, 'name'=>'田中一郎', 'email'=>'taro11@example.com']);
        $response -> assertSessionHas("status-error", "更新対象が自身のプロフィールではないため、処理が失敗しました。。");
        $response -> assertRedirect('/companies');
    }

    public function connectEditStudentProfileByOtherStudent(){
        $student = User::where('is_teacher',0)->first();
        $other_student = User::where('id', '<>', $student->id);
        $response = $this
            ->actingAs($other_student)
            ->get('users/'.$student->id.'/edit');
        $response->assertSessionHas("status-error", "自身のプロフィール以外編集できません。");
        $response->assertRedirect('/companies');
    }

    public function updateStudentProfileByOtherTeacher(){
        $student = User::where('is_teacher', 0)->first();
        $other_student = User::where('id', '<>', $student->id);
        $response = $this
            ->actingAs($other_student)
            ->put(route('users.updateStudentProfile', $student->id), ['attend_num'=>11, 'name'=>'田中一郎', 'email'=>'taro11@example.com']);
        $response -> assertSessionHas("status-error", "更新対象が自身のプロフィールではないため、処理が失敗しました。。");
        $response -> assertRedirect('/companies');
    }

}
