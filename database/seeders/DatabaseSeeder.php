<?php

namespace Database\Seeders;

use App\Models\UserAction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new UserSeeder())->run();
        (new UserActionSeeder())->run();
        (new NoteSeeder())->run();
        (new ContentSeeder())->run();
        (new CommentSeeder())->run();
    }
}
