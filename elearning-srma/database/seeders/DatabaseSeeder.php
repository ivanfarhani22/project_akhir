<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin E-Learning (only)
        $admin = User::create([
            'name' => 'Admin E-Learning',
            'email' => 'admin@elearning.local',
            'password' => bcrypt('password123'),
            'role' => 'admin_elearning',
        ]);

        // 2. Create default settings
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'E-Learning SRMA 25 Lamongan']);
        Setting::updateOrCreate(['key' => 'app_description'], ['value' => 'Platform pembelajaran elektronik SRMA 25 Lamongan']);
        Setting::updateOrCreate(['key' => 'login_banner'], ['value' => '']);
        Setting::updateOrCreate(['key' => 'school_name'], ['value' => 'SRMA 25 Lamongan']);

        echo "✅ Database seeding completed successfully!\n";
        echo "📝 Admin Account Created:\n";
        echo "   Email: admin@elearning.local\n";
        echo "   Password: password123\n";
    }
}
