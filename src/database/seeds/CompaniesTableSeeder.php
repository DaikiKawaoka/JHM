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
        $names = ['test1株式会社','test2株式会社','test3株式会社'];
        $prefectures = ['愛媛','大阪','東京'];
        for ($i = 0; $i<3; $i++) {
            DB::table('companies')->insert([
                'name' => $names[$i],
                'prefecture' => $prefectures[$i],
                'create_user_id' => 1,
            ]);
        }
    }
}
