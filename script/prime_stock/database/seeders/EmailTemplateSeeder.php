<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailTemplate = [
            [
                'name' => 'Welcome Mail',
                'subject' => 'Your Account has been created',
                'content' => '<p><span class="ql-size-large">Welcome to [[company_name]]. </span></p><p>Your account has been created successfully. Please login to your account using your email and password.</p><p>Your login credentials are as follows:</p><p>Email: [[email]]</p><p>Password: [[password]]</p><p>Please change your password after login.</p><p>Login Url: <a href="[[url]]" rel="noopener noreferrer" target="_blank">Login</a></p><p>Thanks</p><p>[[company_name]]</p><p><br></p>',
                'type' => 'default',
            ],
        ];

        foreach ($emailTemplate as $email) {
            EmailTemplate::create($email);
        }
    }
}
