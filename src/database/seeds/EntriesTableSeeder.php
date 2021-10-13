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
        for ($i = 1; $i<=9; $i++) {
            for($j = 1; $j<=2; $j++){
                DB::table('entries')->insert([
                    'student_id' => $i,
                    'company_id' => rand(1,9),
                ]);
            }
        }
    }
}
