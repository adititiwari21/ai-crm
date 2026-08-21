@extends('layouts.app')

@section('title', 'SaaS Purchase Ingestion & Webhooks - CRM Pro')

@section('content')
<style>
    .integration-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .integration-grid {
            grid-template-columns: 1fr;
        }
    }

    .code-box {
        background: #090d16;
        color: #38bdf8;
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        font-family: monospace;
        font-size: 12.5px;
        line-height: 1.5;
        overflow-x: auto;
        position: relative;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">SaaS Purchase Ingestion & Webhooks</h1>
        <p class="page-subtitle">Automatically capture website SaaS monthly subscriptions directly into your CRM database in real-time.</p>
    </div>
</div>

<div class="integration-grid">
    <!-- Card 1: Live Purchase Simulator -->
    <div class="card card-p">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <div style="width: 34px; height: 34px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                <i data-lucide="play-circle" style="width: 20px; height: 20px;"></i>
            </div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main);">Simulate Live Website Purchase</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Test buying a SaaS monthly plan to see instant CRM synchronization.</span>
            </div>
        </div>

        <form action="{{ route('saas.purchase.simulate') }}" method="POST">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Customer Name *</label>
                        <input type="text" name="name" class="form-control" value="Rohan Verma" required>
                    </div>
                    <div>
                        <label class="form-label">Customer Email *</label>
                        <input type="email" name="email" class="form-control" value="rohan@techscale.io" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company" class="form-control" value="TechScale Cloud Solutions">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="+91 98765 11223">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">SaaS Subscription Plan *</label>
                        <select name="plan_name" class="form-control" onchange="updateSimPrice(this.value)">
                            <option value="SaaS Starter Plan">Starter Plan ($29/mo)</option>
                            <option value="SaaS AI Pro Monthly" selected>AI Pro Monthly ($99/mo)</option>
                            <option value="Enterprise Custom Cluster">Enterprise Custom ($299/mo)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Billing Cycle</label>
                        <select name="billing_cycle" class="form-control">
                            <option value="monthly" selected>Monthly</option>
                            <option value="annual">Annual</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Amount ($) *</label>
                        <input type="number" step="0.01" name="amount" id="simAmount" class="form-control" value="99.00" required>
                    </div>
                    <div>
                        <label class="form-label">Payment Gateway</label>
                        <select name="payment_gateway" class="form-control">
                            <option value="Stripe Checkout">Stripe Checkout</option>
                            <option value="Razorpay">Razorpay</option>
                            <option value="LemonSqueezy">LemonSqueezy</option>
                            <option value="Direct Website Checkout">Direct Website Checkout</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 6px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                        <i data-lucide="zap" style="width: 16px; height: 16px;"></i>
                        <span>Simulate Customer Buying SaaS Plan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Card 2: Webhook Endpoint & Integration Snippet -->
    <div class="card card-p">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <div style="width: 34px; height: 34px; border-radius: 8px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #10b981;">
                <i data-lucide="webhook" style="width: 20px; height: 20px;"></i>
            </div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main);">Live Ingestion Webhook URL</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Point your website payment gateway or backend to this URL.</span>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <label class="form-label">Webhook POST Endpoint:</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="webhookUrlInput" class="form-control" value="{{ url('/api/webhooks/saas-purchase') }}" readonly style="font-family: monospace; font-size: 13px; font-weight: 600; color: var(--primary);">
                <button type="button" class="btn btn-secondary" onclick="copyWebhookUrl()">
                    <i data-lucide="copy" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
        </div>

        <div>
            <label class="form-label">Sample JSON Webhook Payload:</label>
            <div class="code-box">
{
  "email": "customer@company.com",
  "name": "Jane Smith",
  "company": "Smith Tech Inc.",
  "phone": "+1 555 123 4567",
  "plan_name": "SaaS AI Pro Monthly",
  "billing_cycle": "monthly",
  "amount": 99.00,
  "currency": "USD",
  "payment_gateway": "Stripe",
  "transaction_id": "ch_3N92kL2eZvKYlo2C1g"
}
            </div>
        </div>

        <div style="margin-top: 14px; font-size: 12px; color: var(--text-muted); line-height: 1.5;">
            ✅ <strong>What happens when called:</strong> Instantly creates Client account, records Paid Sale, generates itemized Paid Invoice, creates Won Deal in pipeline, and logs activity to 360° timeline.
        </div>
    </div>
</div>

<!-- Table: Recent Ingested SaaS Purchases -->
<div class="card" style="overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main);">Recent Automated SaaS Purchases</h3>
    </div>
    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Purchase Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPurchases as $p)
                    <tr>
                        <td style="font-weight: 700;">{{ $p->client->name ?? 'Customer' }}</td>
                        <td>{{ $p->client->company ?? 'Company' }}</td>
                        <td>{{ $p->client->email ?? 'N/A' }}</td>
                        <td style="font-family: var(--font-heading); font-weight: 800; color: var(--success); font-size: 15px;">
                            ${{ number_format($p->amount, 2) }}
                        </td>
                        <td>{{ $p->sale_date ? \Carbon\Carbon::parse($p->sale_date)->format('M d, Y') : 'Today' }}</td>
                        <td><span class="badge badge-paid">Paid & Synced</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            No SaaS purchases recorded yet. Use the simulator above or trigger the webhook!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function updateSimPrice(plan) {
        const input = document.getElementById('simAmount');
        if (plan.includes('Starter')) input.value = '29.00';
        else if (plan.includes('Pro')) input.value = '99.00';
        else if (plan.includes('Enterprise')) input.value = '299.00';
    }

    function copyWebhookUrl() {
        const input = document.getElementById('webhookUrlInput');
        navigator.clipboard.writeText(input.value);
        alert('📋 Webhook URL copied to clipboard!');
    }
</script>
@endpush
@endsection
