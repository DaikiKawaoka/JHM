<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefectures = ['愛媛','大阪','東京','愛媛'];
        DB::table('student_companies')->insert([
            'name' => '株式会社ソルト',
            'prefecture' => $prefectures[2],
            'create_student_id' => 8,
        ]);
        DB::table('student_companies')->insert([
            'name' => 'freee',
            'prefecture' => $prefectures[2],
            'create_student_id' => 27,
        ]);
    }
}
