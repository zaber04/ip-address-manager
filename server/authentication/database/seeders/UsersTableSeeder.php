<?php

namespace AuthenticationDatabase\Seeders;

use Authentication\Models\User;
use AuthenticationDatabase\Factories\UserFactory;

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
        $numUsers = 10;
        User::factory($numUsers)->create();
    }
}
