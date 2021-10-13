<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        // $this->call(FavoritesTableSeeder::class);
        $this->call(StudentsSeeder::class);
        $this->call(WorkSpacesTableSeeder::class);
        $this->call(MembershipTableSeeder::class);
        $this->call(EntriesTableSeeder::class);
        $this->call(ProgressTableSeeder::class);
    }
}
