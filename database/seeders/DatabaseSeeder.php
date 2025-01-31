<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AuthorSeeder::class,
            BookSeeder::class,
            CategorySeeder::class,
            BookCategorySeeder::class,
            UserSeeder::class,
            RateSeeder::class,
            CommentSeeder::class,
            FavoriteSeeder::class,
            SellSeeder::class

        ]);
    }
}
