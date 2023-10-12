<?php

namespace Database\Seeders;

use App\Models\Subcat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubcatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subcat::factory()
        ->count(6)
        ->create();
    }
}
