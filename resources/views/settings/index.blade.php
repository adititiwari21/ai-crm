@extends('layouts.app')

@section('title', 'CRM Pro Settings & Configurations')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h1 class="page-title">Settings & System Configurations</h1>
        <p class="page-subtitle">Manage company details, currency preferences, Gemini AI keys, and webhook secrets.</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Section 1: Company Profile -->
            <div class="card card-p">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="building" style="width: 18px; height: 18px; color: var(--primary);"></i>
                    Company & Invoicing Profile
                </h3>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="form-label">Administrator Display Name *</label>
                            <input type="text" name="admin_name" class="form-control" value="{{ $setting->admin_name ?? 'Administrator' }}" placeholder="e.g. Aditi Tiwari" required>
                        </div>
                        <div>
                            <label class="form-label">Admin Role / Title</label>
                            <input type="text" name="admin_role" class="form-control" value="{{ $setting->admin_role ?? 'Super Admin' }}" placeholder="e.g. CEO & Founder">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Company Name *</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $setting->company_name }}" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="form-label">Support / Billing Email *</label>
                            <input type="email" name="company_email" class="form-control" value="{{ $setting->company_email }}" required>
                        </div>
                        <div>
                            <label class="form-label">Company Phone</label>
                            <input type="text" name="company_phone" class="form-control" value="{{ $setting->company_phone }}">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="form-label">Currency Code</label>
                            <select name="currency" class="form-control">
                                <option value="USD" {{ $setting->currency === 'USD' ? 'selected' : '' }}>USD (United States Dollar)</option>
                                <option value="INR" {{ $setting->currency === 'INR' ? 'selected' : '' }}>INR (Indian Rupee)</option>
                                <option value="EUR" {{ $setting->currency === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="GBP" {{ $setting->currency === 'GBP' ? 'selected' : '' }}>GBP (British Pound)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="{{ $setting->currency_symbol }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: AI & Gemini API Settings -->
            <div class="card card-p">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="sparkles" style="width: 18px; height: 18px; color: #8b5cf6;"></i>
                    Google Gemini AI Intelligence Configuration
                </h3>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="form-label">Gemini API Key (Optional)</label>
                        <input type="password" name="gemini_api_key" class="form-control" value="{{ $setting->gemini_api_key }}" placeholder="AIzaSy...">
                        <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px; display: block;">
                            If left blank, the CRM utilizes its built-in local database reasoning engine with 100% functionality.
                        </span>
                    </div>

                    <div>
                        <label class="form-label">AI Model</label>
                        <select name="gemini_model" class="form-control">
                            <option value="gemini-2.5-flash" {{ $setting->gemini_model === 'gemini-2.5-flash' ? 'selected' : '' }}>Gemini 2.5 Flash (Ultra Fast, Default)</option>
                            <option value="gemini-2.5-pro" {{ $setting->gemini_model === 'gemini-2.5-pro' ? 'selected' : '' }}>Gemini 2.5 Pro (Deep Reasoning)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    <span>Save All Settings</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
