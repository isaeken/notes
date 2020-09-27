<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

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
            'content' => $this->faker->text(200),
        ];
    }
}
