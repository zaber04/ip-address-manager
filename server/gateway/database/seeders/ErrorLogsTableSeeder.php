<?php

namespace Database\Seeders;

use Zaber04\LumenApiResources\Models\ErrorLog;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;


class ErrorLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfRecords = 10;
        $faker = FakerFactory::create();

        for ($i = 0; $i < $numberOfRecords; $i++) {
            ErrorLog::create([
                'url'          => $faker->url,
                'param'        => $faker->text,
                'body'         => $faker->text,
                'controller'   => $faker->word,
                'functionName' => $faker->word,
                'statusCode'   => $faker->randomNumber(3),
                'message'      => $faker->sentence,
                'error'        => $faker->paragraph,
                'ip'           => $faker->ipv4
            ]);
        }
    }
}

// php artisan db:seed --class=Database\Seeders\ErrorLogsTableSeeder
