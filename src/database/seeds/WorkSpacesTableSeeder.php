<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkSpacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $class_names = ['ITエンジニア科1','ITエンジニア科2'];
        $years = ['2020','2021'];
        for ($i = 0; $i<2; $i++) {
            DB::table('workspaces')->insert([
                'class_name' => $class_names[$i],
                'year' => $years[$i],
                'teacher_id' => 1,
            ]);
        }

        $class_names = ['ゲームクリエイター科1'];
        $years = ['2019'];
        for ($i = 0; $i<1; $i++) {
            DB::table('workspaces')->insert([
                'class_name' => $class_names[$i],
                'year' => $years[$i],
                'teacher_id' => 2,
            ]);
        }
    }
}
