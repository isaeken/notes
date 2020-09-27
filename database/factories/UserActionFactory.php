<?php

namespace Database\Factories;

use App\Enums\LogLevel;
use App\Models\User;
use App\Models\UserAction;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'level' => LogLevel::getRandomValue(),
            'user_id' => User::all()->random(1)->first()->id,
            'message' => $this->faker->text,
        ];
    }
}
