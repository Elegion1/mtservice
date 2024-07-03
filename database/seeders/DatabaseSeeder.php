<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        File::cleanDirectory(public_path('storage'));
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(DestinationSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(OwnerDataSeeder::class);
        $this->call(ExcursionSeeder::class);
        $this->call(PartnerSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ContentSeeder::class);
        $this->call(PageSeeder::class);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
