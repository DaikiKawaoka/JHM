<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['test1株式会社','test2株式会社','test3株式会社','test4株式会社','test5株式会社','test6株式会社','test7株式会社','test8株式会社','test9株式会社','test10株式会社','test11株式会社','test12株式会社'];
        $prefectures = ['愛媛','大阪','東京','愛媛'];
        for ($i = 0; $i<12; $i++) {
            DB::table('companies')->insert([
                'name' => $names[$i],
                'prefecture' => $prefectures[($i%4)],
                'create_user_id' => ($i%2)+1,
            ]);
        }
    }
}
