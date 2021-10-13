<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = ['田中太郎','山田太郎','佐藤太郎'];
        $emails = ['taro1@example.com','taro2@example.com','taro3@example.com'];
        for ($i = 0; $i<3; $i++) {
            DB::table('students')->insert([
                'attend_num' => $i+1,
                'name' => $students[$i],
                'email' => $emails[$i],
                'password' => Hash::make('password'),
            ]);
        }

        $students = ['中田太郎','中田二郎','中田三郎'];
        $emails = ['nakata1@example.com','nakata2@example.com','nakata3@example.com'];
        for ($i = 0; $i<3; $i++) {
            DB::table('students')->insert([
                'attend_num' => $i+4,
                'name' => $students[$i],
                'email' => $emails[$i],
                'password' => bcrypt('password'),
            ]);
        }

        $students = ['ゲーム太郎','ゲーム二郎','ゲーム三郎'];
        $emails = ['game1@example.com','game2@example.com','game3@example.com'];
        for ($i = 0; $i<3; $i++) {
            DB::table('students')->insert([
                'attend_num' => $i+7,
                'name' => $students[$i],
                'email' => $emails[$i],
                'password' => bcrypt('password'),
            ]);
        }
    }
}
