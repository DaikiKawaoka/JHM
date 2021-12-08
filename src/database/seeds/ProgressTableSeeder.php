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
        $actions = ['説明会','面接','社長面接'];
        $action_dates = ['2021-11-01','2021-12-02','2021-10-03'];
        $states = ['◯','◯','×'];
        for ($i = 0; $i<9; $i++) {
            for($j = 0; $j<2; $j++){
                DB::table('progress')->insert([
                    'student_id' => $i+1,
                    'entry_id' => $j+($i*2)+1,
                    'action' => $actions[$j],
                    'state' => $states[$j],
                    'action_date' => $action_dates[$j],
                ]);
            }
        }
    }
}
