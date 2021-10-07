<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i<=3; $i++) {
            DB::table('favorites')->insert([
                'user_id' => $i+2, //id1,2は先生だからプラス2している
                'company_id' => $i,
            ]);
        }
    }
}
