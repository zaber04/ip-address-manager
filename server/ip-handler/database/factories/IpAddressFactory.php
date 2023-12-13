<?php

namespace Database\Factories;

use IpHandler\Models\IpAddress;

use Illuminate\Database\Eloquent\Factories\Factory;

class IpAddressFactory extends Factory
{
    protected $model = IpAddress::class;

    public function definition(): array
    {
        return [
            'ip'    => $this->faker->ipv4,
            'label' => $this->faker->word
        ];
    }
}
