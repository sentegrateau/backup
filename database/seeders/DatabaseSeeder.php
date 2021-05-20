<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\PostFactory;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       DB::table('users')->insert([
           'name' => 'sentegrate',
           'role' => 'partner'
       ]);

    }
}
