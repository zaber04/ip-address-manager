<?php

namespace GatewayDatabase\Seeders;


use Gateway\Models\ErrorLog;

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

        // factory(ErrorLog::class, $numberOfRecords)->create();
        // ErrorLog::factory()->count($numberOfRecords)->make();

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
