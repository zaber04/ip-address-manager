<?php

namespace Database\Seeders;

use Database\Factories\IpAddressFactory;

use Illuminate\Database\Seeder;


class IpAddressSeeder extends Seeder
{
    public function run()
    {
        $count = 10;
        IpAddressFactory::new()->count($count)->create();
    }
}

// php artisan db:seed --class=IpHandlerDatabase\\Seeders\\IpAddressSeeder
