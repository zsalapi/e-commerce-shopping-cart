<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Admin User
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret1234'), // Use a secure password
            'is_admin' => true,
        ]);

        // 2. Create a Test Simple User (Optional, for easy testing)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('secret1234'),
            'is_admin' => false,
        ]);

        // 3. Create 15 types of Products using the Factory
        Product::factory()->count(15)->create();
    }
}
