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
                'user_id' => $i+1, //id1は先生だからプラスしている
                'company_id' => $i,
            ]);
        }
    }
}
