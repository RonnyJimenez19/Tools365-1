<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        // CategoriaSeeder PRIMERO porque ProductoSeeder usa categoria_id (FK)
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}