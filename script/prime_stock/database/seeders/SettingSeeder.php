<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_title',
                'value' => 'Prime Stock',
            ],
            [
                'key' => 'currency',
                'value' => 'USD',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Dhaka',
            ],
            [
                'key' => 'record_to_display',
                'value' => '10',
            ],
            [
                'key' => 'currency_format',
                'value' => 'text',
            ],
            [
                'key' => 'footer_text',
                'value' => '©Pixel Prime Soft - All Right Reserved',
            ],
            [
                'key' => 'company_name',
                'value' => 'Pixel Prime Soft',
            ],
            [
                'key' => 'company_address',
                'value' => 'Dhaka, Bangladesh',
            ],
            [
                'key' => 'company_email',
                'value' => 'info@pixelprimesoft.com',
            ],
            [
                'key' => 'company_phone',
                'value' => '+8801700000000',
            ],
            [
                'key' => 'default_customer',
                'value' => '1',
            ],
            [
                'key' => 'default_warehouse',
                'value' => '1',
            ],
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
            ],
            [
                'key' => 'mail_username',
                'value' => 'null',
            ],
            [
                'key' => 'mail_password',
                'value' => 'null',
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'null',
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'info@pixelprimesoft.com',
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Prime Stock',
            ],
            [
                'key' => 'logo',
                'value' => 'logo.png',
            ],
            [
                'key' => 'favicon',
                'value' => 'favicon.png',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
