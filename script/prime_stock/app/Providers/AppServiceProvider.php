<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (file_exists(storage_path('installed'))) {
            config()->set([
                'app.locale' => Language::where('is_default', '=', true)->first('locale')->locale ?? 'en',
            ]);

            $widgetArray = [
                'total_products' => 'on',
                'total_customers' => 'on',
                'total_suppliers' => 'on',
                'total_category' => 'on',
                'total_sale' => 'on',
                'total_sale_amount' => 'on',
                'total_sale_return' => 'on',
                'total_sale_return_amount' => 'on',
                'total_purchase' => 'on',
                'total_purchase_amount' => 'on',
                'total_purchase_return' => 'on',
                'total_purchase_return_amount' => 'on',
                'purchase_sale_report' => 'on',
                'top_selling_product' => 'on',
                'stock_level_alert' => 'on',
            ];
            session()->put('widget', $widgetArray);

            // email config
            $emailConfig = [
                'driver' => settings('mail_driver'),
                'host' => settings('mail_host'),
                'port' => settings('mail_port'),
                'username' => settings('mail_username'),
                'password' => settings('mail_password'),
                'encryption' => settings('mail_encryption'),
            ];
            config()->set('mail.mailers.smtp.host', $emailConfig['host']);
            config()->set('mail.mailers.smtp.port', $emailConfig['port']);
            config()->set('mail.mailers.smtp.username', $emailConfig['username']);
            config()->set('mail.mailers.smtp.password', $emailConfig['password']);
            config()->set('mail.mailers.smtp.encryption', $emailConfig['encryption']);
            config()->set('mail.from.address', settings('mail_from_address'));
            config()->set('mail.from.name', settings('mail_from_name'));
        }
    }
}
