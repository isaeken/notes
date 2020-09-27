<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Content::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random(1)->first()->id,
            'note_id' => Note::all()->random(1)->first()->id,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'content' => $this->faker->text(5000),
        ];
    }
}
