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
        // 3年制
        for ($i = 0; $i<24; $i++) {
            DB::table('membership')->insert([
                'workspace_id' => 1,
                'student_id' => $i+1,
            ]);
        }
        // 4年制
        for ($i = 0; $i<10; $i++) {
            DB::table('membership')->insert([
                'workspace_id' => 2,
                'student_id' => $i+25,
            ]);
        }
    }
}
