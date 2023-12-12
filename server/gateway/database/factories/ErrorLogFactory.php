<?php

namespace Database\Factories;


use Gateway\Models\ErrorLog;

use Faker\Factory as FakerFactory;
// use Illuminate\Database\Eloquent\Factories\Factory;


$factory->define(ErrorLog::class, function () {
    $faker = FakerFactory::create();

    return [
        'url'          => $faker->url,
        'param'        => $faker->text,
        'body'         => $faker->text,
        'controller'   => $faker->word,
        'functionName' => $faker->word,
        'statusCode'   => $faker->randomNumber(3),
        'message'      => $faker->sentence,
        'error'        => $faker->paragraph,
        'ip'           => $faker->ipv4
    ];
});

