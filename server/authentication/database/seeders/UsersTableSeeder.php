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
        User::create([
            'first_name' => 'admin',
            'last_name'  => 'user',
            'email'      => 'admin.user@example.com',
            'password'   => 'secret_password',
            'contact'    => '',
            'address'    => '',
        ]);

        $numUsers = 10;
        User::factory($numUsers)->create();
    }
}
