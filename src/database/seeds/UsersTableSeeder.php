<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => '先生太郎',
            'class' => 'ITエンジニア',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
            'is_teacher' => 1,
        ]);
        DB::table('users')->insert([
            'name' => '先生太郎2',
            'class' => 'ITイノベーション',
            'email' => 'teacher2@example.com',
            'password' => bcrypt('password'),
            'is_teacher' => 1,
        ]);

        $students = ['田中太郎','山田太郎','佐藤太郎'];
        $emails = ['taro1@example.com','taro2@example.com','taro3@example.com'];
        for ($i = 0; $i<3; $i++) {
            DB::table('users')->insert([
                'attend_num' => $i+1,
                'name' => $students[$i],
                'email' => $emails[$i],
                'password' => bcrypt('password'),
                'teacher_id' => 1,
                'is_teacher' => 0,
            ]);
        }

        $students = ['中田太郎','中田二郎','中田三郎'];
        $emails = ['nakata1@example.com','nakata2@example.com','nakata3@example.com'];
        for ($i = 0; $i<3; $i++) {
            DB::table('users')->insert([
                'attend_num' => $i+4,
                'name' => $students[$i],
                'email' => $emails[$i],
                'password' => bcrypt('password'),
                'teacher_id' => 2,
                'is_teacher' => 0,
            ]);
        }
    }
}
