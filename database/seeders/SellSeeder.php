<?php

namespace Database\Seeders;

use App\Models\Sell;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sell::factory()->count(500)->create();
    }
}
