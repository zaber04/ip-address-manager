<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use IpHandler\Models\IpAddress;

class IpAddressesTableSeeder extends Seeder
{
    public function run()
    {
        $count = 100;
        IpAddress::factory($count)->create();
    }
}
