<?php

namespace Database\Seeders;

use Authentication\Models\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $numUsers = 10;
        User::factory($numUsers)->create();
    }
}
