<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'jhonny andres ibañez garzon',
            'email' => 'jhandres187@gmail.com',
            'password' => Hash::make('Luceand21'),
            'rol' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'andres felipe reyes fonseca',
            'email' => 'afro@gmail.com',
            'password' => Hash::make('Luceand21'),
            'rol' => 'regular'
        ]);
        Category::factory(4)->create();
        Post::factory(10000)->create();
    }
}
