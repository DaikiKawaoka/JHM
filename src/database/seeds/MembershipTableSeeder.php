<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i<3; $i++) {
            for($j = 1; $j<4; $j++){
                DB::table('membership')->insert([
                    'workspace_id' => $i+1,
                    'student_id' => $j+($i*3),
                ]);
            }
        }
    }
}
