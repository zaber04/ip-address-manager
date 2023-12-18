<?php

namespace Database\Factories;

use IpHandler\Models\AuditTrail;
use Zaber04\LumenApiResources\Enums\ActionEnum;
use Zaber04\LumenApiResources\Database\Factories\UserFactory;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuditTrailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AuditTrail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'action'        => $this->faker->randomElement(ActionEnum::cases()),
            'property_name' => $this->faker->word,
            'old_data'      => ['label' => 'old_label'],
            'new_data'      => ['label' => 'new_label'],
            'user_id'       => Str::uuid(),
            'session_id'    => $this->faker->uuid,
        ];
    }
}
