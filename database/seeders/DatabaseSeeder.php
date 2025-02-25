<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'brahim@example.com'],
            [
                'name' => 'Brahim',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            SourceSeeder::class,
            CategorySeeder::class,
            // SampleDataSeeder::class,
        ]);
    }
}
