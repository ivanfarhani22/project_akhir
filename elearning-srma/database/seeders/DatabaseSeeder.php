<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one admin exists (but do not delete existing users)
        User::firstOrCreate(
            ['email' => 'admin@elearning.local'],
            [
                'name' => 'Admin E-Learning',
                'password' => bcrypt('password123'),
                'role' => 'admin_elearning',
            ]
        );

        // Default settings (idempotent)
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'E-Learning SRMA 25 Lamongan']);
        Setting::updateOrCreate(['key' => 'app_description'], ['value' => 'Platform pembelajaran elektronik SRMA 25 Lamongan']);
        Setting::updateOrCreate(['key' => 'login_banner'], ['value' => '']);
        Setting::updateOrCreate(['key' => 'school_name'], ['value' => 'SRMA 25 Lamongan']);

        // Clear existing e-learning data (users kept)
        $this->call(ClearDataSeeder::class);

        // Seed subjects based on the provided KBM list
        $this->call(CreateSubjectsFromKbmSeeder::class);

        // Seed asrama (non-academic) activities as Subject entities
        $this->call(CreateAsramaSubjectsSeeder::class);

        $this->command->info('✅ Database seeding completed. Users preserved; subjects seeded from KBM list + asrama activities.');
    }
}
