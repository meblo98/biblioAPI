<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\CategorieSeeder;
use Database\Seeders\LivreSeeder;
use Database\Seeders\Userseeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
     $this->call([
        Userseeder::class,
        CategorieSeeder::class,
        LivreSeeder::class
     ]);
    }
}
