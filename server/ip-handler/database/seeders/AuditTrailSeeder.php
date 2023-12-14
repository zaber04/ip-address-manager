<?php

namespace IpHandlerDatabase\Seeders;

use IpHandlerDatabase\Factories\AuditTrailFactory;

use Illuminate\Database\Seeder;


class AuditTrailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the number of AuditTrails you want to seed
        $count = 10;
        AuditTrailFactory::new()->count($count)->create();
    }
}

// php artisan db:seed --class=IpHandlerDatabase\\Seeders\\AuditTrailSeeder
