<?php

namespace Database\Seeders;

use Authentication\Models\User;
use Database\Factories\UserFactory;

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
        $user = User::firstOrNew([
            'email' => 'admin.user@ip-manager.com',
        ], [
            'first_name' => 'admin',
            'last_name'  => 'user',
            'password'   => 'secret_password',
            'contact'    => '',
            'address'    => '',
        ]);

        if (!$user->exists) {
            $user->save();
        }

        $numUsers = 10;
        User::factory($numUsers)->create();
    }
}
