<?php

namespace Database\Factories;

use App\Enums\NoteType;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => NoteType::getRandomValue(),
            'user_id' => User::all()->random(1)->first()->id,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'comments' => $this->faker->boolean,
            'title' => $this->faker->text(30),
        ];
    }
}
