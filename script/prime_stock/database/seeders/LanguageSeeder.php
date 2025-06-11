<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'name' => 'English',
                'locale' => 'en',
                'status' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Bangla',
                'locale' => 'bn',
                'status' => true,
                'is_default' => false,
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
