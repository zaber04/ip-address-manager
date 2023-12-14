<?php

namespace IpHandlerDatabase\Seeders;

// use IpHandler\Models\IpAddress;
use IpHandlerDatabase\Factories\IpAddressFactory;

use Illuminate\Database\Seeder;


class IpAddressSeeder extends Seeder
{
    public function run()
    {
        $count = 10;
        // IpAddress::factory($count)->create();
        IpAddressFactory::new()->count($count)->create();
    }
}

// php artisan db:seed --class=IpHandlerDatabase\\Seeders\\IpAddressSeeder
