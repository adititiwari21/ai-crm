<?php

namespace App\Http\Controllers;

use App\Models\CrmSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
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
            'webhook_secret' => 'whsec_crm_pro_' . substr(md5(time()), 0, 8),
        ]);

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_role' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:5',
            'gemini_api_key' => 'nullable|string|max:255',
            'gemini_model' => 'required|string|max:50',
        ]);

        $setting = CrmSetting::first();
        if ($setting) {
            $setting->update($validated);
        }

        return redirect()->back()->with('success', 'CRM Settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_role' => 'nullable|string|max:255',
        ]);

        $setting = CrmSetting::first();
        if ($setting) {
            $setting->update($validated);
        }

        return redirect()->back()->with('success', "Profile updated! Hello, {$validated['admin_name']}.");
    }
}
