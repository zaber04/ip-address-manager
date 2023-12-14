<?php

namespace IpHandlerDatabase\Factories;

use Gateway\Enums\ActionEnum;
use IpHandler\Models\AuditTrail;
use AuthenticationDatabase\Factories\UserFactory;


use Illuminate\Database\Eloquent\Factories\Factory;


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
            'user_id'       => function () {
                return UserFactory::new()->create()->id;
                // instead of making an API request to auth service, we used userfactory directly.
                // this choice is used for only reating test data and not real usecase
            },
            'session_id'    => $this->faker->uuid,
        ];
    }
}
