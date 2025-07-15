<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductAttributesSeeder::class);
        $this->call(ShippingMethodSeeder::class);



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'devmomina@gmail.com',
            'password' => bcrypt('password'),
            'usertype' => 'admin',
        ]);
    }
}
