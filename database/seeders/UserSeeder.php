<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $count
     * @return void
     */
    public function run(int $count = 10)
    {
        User::factory($count)->create();
    }
}
