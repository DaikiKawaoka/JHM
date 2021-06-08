<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evens = ['会社説明会','一次面接','最終面接'];
        $states = ['参加','合格','不合格'];
        for ($i = 1; $i<=3; $i++) {
            DB::table('progress')->insert([
                'user_id' => $i+1, //id1は先生だからプラスしている
                'entry_id' => $i,
                'event' => $evens[$i-1],
                'state' => $states[$i-1],
            ]);
        }
    }
}
