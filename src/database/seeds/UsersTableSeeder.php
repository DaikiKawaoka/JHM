<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => '那須道生',
            'email' => 'm-nasu@kawahara.ac.jp',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '梶田一貴',
            'email' => 'k-kajita@kawahara.ac.jp',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '大瀧一幸',
            'email' => 'k-ootaki@kawahara.ac.jp',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '森敏',
            'email' => 's-mori@kawahara.ac.jp',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '先生太郎',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
