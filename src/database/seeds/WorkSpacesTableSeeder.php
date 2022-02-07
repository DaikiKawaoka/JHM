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
        $class_name = 'ITエンジニア';
        $years = ['2022','2023'];
        for ($i = 0; $i<2; $i++) {
            DB::table('workspaces')->insert([
                'class_name' => $class_name,
                'year' => $years[$i],
                'teacher_id' => 1,
            ]);
        }

        $years = ['2022'];
        for ($i = 0; $i<1; $i++) {
            DB::table('workspaces')->insert([
                'class_name' => $class_name,
                'year' => $years[$i],
                'teacher_id' => 3,
            ]);
        }
    }
}
