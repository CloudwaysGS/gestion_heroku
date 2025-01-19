<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Expense::factory()->count(10)->create();

        $this->call([
            UserSeeder::class, // Appelle le seeder pour les utilisateurs
        ]);
    }
}
