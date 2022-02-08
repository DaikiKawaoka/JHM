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
        $names = ['株式会社よんやく','デジタル・インフォメーション・テクノロジー','インフォコム西日本','日本プレースメントセンター','日本クリエイティブシステム株式会社','test6株式会社','test7株式会社','test8株式会社','test9株式会社','test10株式会社','test11株式会社','test12株式会社'];
        $prefectures = ['愛媛','大阪','東京','愛媛'];
        for ($i=5; $i<12; $i++) {
            DB::table('companies')->insert([
                'name' => $names[$i],
                'prefecture' => $prefectures[($i%4)],
                'create_user_id' => ($i%2)+1,
            ]);
        }
        for($i=0; $i<5; $i++){
            DB::table('companies')->insert([
                'name' => $names[$i],
                'prefecture' => $prefectures[0],
                'create_user_id' => 1,
            ]);
        }
    }
}
