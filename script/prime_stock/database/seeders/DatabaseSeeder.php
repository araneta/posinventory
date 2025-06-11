<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\EmailTemplate;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmailTemplateSeeder::class,
            SettingSeeder::class,
            LanguageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
        ]);
    }
}
