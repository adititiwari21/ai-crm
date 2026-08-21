<?php

namespace App\Providers;

use App\Models\CrmSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        // Globally share CRM Settings with all Blade views
        View::composer('*', function ($view) {
            if (Schema::hasTable('crm_settings')) {
                $setting = CrmSetting::firstOrCreate([], [
                    'admin_name' => 'Administrator',
                    'admin_role' => 'Super Admin',
                    'company_name' => 'CRM Pro Enterprises',
                    'company_email' => 'admin@crmpro.ai',
                    'company_phone' => '+1 (555) 000-1234',
                    'currency' => 'USD',
                    'currency_symbol' => '$',
                    'gemini_api_key' => env('GEMINI_API_KEY'),
                    'gemini_model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
                    'webhook_secret' => 'whsec_crm_pro_9981',
                ]);
                $view->with('crmSetting', $setting);
            }
        });
    }
}
