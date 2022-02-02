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
        // $students = ['田中太郎','山田太郎','佐藤太郎'];
        // $emails = ['taro1@example.com','taro2@example.com','taro3@example.com'];
        // for ($i = 0; $i<3; $i++) {
        //     DB::table('students')->insert([
        //         'attend_num' => $i+1,
        //         'name' => $students[$i],
        //         'email' => $emails[$i],
        //         'password' => Hash::make('password'),
        //     ]);
        // }

        // 3年制
        $students = ['井川大貴','稲井新','岩瀬徳来','大内伊織','大田浩貴','沖田祐輝','蔭谷奏麿','川岡大輝','清田愛生','合田友','坂之上英斗','﨑本璃音','清水敬太','酒肆悠登','谷井愛歩','中居健太郎','野村弘人','長谷部永宝','三宅大夢','村上詩音','森田栄一','山浦結翔','和田任馬','冨永真未'];
        $attend_nums = ['01','02','04','05','06','07','09','11','13','14','15','16','17','18','20','22','26','27','31','32','33','34','35','37',];
        $format = 'kbc19a%s@stu.kawahara.ac.jp';
        for ($i = 0; $i< count($students) ; $i++) {
            DB::table('students')->insert([
                'attend_num' => $attend_nums[$i],
                'name' => $students[$i],
                'email' => sprintf($format, $attend_nums[$i]),
                'password' => Hash::make('password'),
            ]);
        }
        // 4年制
        $students = ['加藤悠人','菊池太成','千原吉彦','中須賀文哉','中田誠一郎','西田雅哉','藤本龍馬','星野友明','本田和輝','谷﨑春香'];
        $attend_nums = ['10','12','21','23','24','25','28','29','30','36'];
        for ($i = 0; $i < count($students); $i++) {
            DB::table('students')->insert([
                'attend_num' => $attend_nums[$i],
                'name' => $students[$i],
                'email' => sprintf($format, $attend_nums[$i]),
                'password' => Hash::make('password'),
            ]);
        }
    }
}
