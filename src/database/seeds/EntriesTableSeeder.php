<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i<=3; $i++) {
            DB::table('entries')->insert([
                'user_id' => $i+1,
                'company_id' => $i,
            ]);
        }
    }
}
