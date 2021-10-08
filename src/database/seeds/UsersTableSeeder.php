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
            'name' => '先生太郎',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '先生太郎2',
            'email' => 'teacher2@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
