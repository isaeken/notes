<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $count
     * @return void
     */
    public function run(int $count = 10)
    {
        \App\Models\UserAction::factory($count)->create();
    }
}
