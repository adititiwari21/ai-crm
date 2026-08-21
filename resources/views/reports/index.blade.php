@extends('layouts.app')

@section('title', 'Executive Reports & Analytics - CRM Pro')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Reports & Business Analytics</h1>
        <p class="page-subtitle">Deep financial telemetry, deal velocity, win-rate metrics, and revenue projections.</p>
    </div>

    <div>
        <button type="button" class="btn btn-secondary" onclick="window.print()">
            <i data-lucide="printer" style="width: 15px; height: 15px;"></i>
            <span>Print Report</span>
        </button>
    </div>
</div>

<!-- KPI Cards -->
<div class="stat-cards-grid" style="margin-bottom: 24px;">
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">Total Realized Revenue</div>
            <div class="stat-value-text" style="color: var(--success);">${{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="stat-icon-wrapper stat-icon-green"><i data-lucide="dollar-sign" style="width: 20px; height: 20px;"></i></div>
    </div>
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">Pending Receivables</div>
            <div class="stat-value-text" style="color: var(--warning);">${{ number_format($pendingRevenue, 2) }}</div>
        </div>
        <div class="stat-icon-wrapper stat-icon-orange"><i data-lucide="clock" style="width: 20px; height: 20px;"></i></div>
    </div>
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">Active Pipeline Value</div>
            <div class="stat-value-text" style="color: var(--primary);">${{ number_format($pipelineValue, 2) }}</div>
        </div>
        <div class="stat-icon-wrapper stat-icon-purple"><i data-lucide="trending-up" style="width: 20px; height: 20px;"></i></div>
    </div>
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">Deal Win Rate</div>
            <div class="stat-value-text">{{ $conversionRate }}%</div>
        </div>
        <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0284c7;"><i data-lucide="award" style="width: 20px; height: 20px;"></i></div>
    </div>
</div>

<!-- Performance Tables Grid -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- Revenue Breakdown by Client -->
    <div class="card" style="overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); font-weight: 700;">
            Top Clients by Lifetime Revenue
        </div>
        <div class="table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Company</th>
                        <th>Lifetime Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $c)
                        <tr>
                            <td style="font-weight: 600;">{{ $c->name }}</td>
                            <td>{{ $c->company ?: 'N/A' }}</td>
                            <td style="font-weight: 800; color: var(--success);">${{ number_format($c->lifetime_value, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;">No client records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Closed Won Deals -->
    <div class="card" style="overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); font-weight: 700;">
            Closed Won Opportunities
        </div>
        <div class="table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Deal</th>
                        <th>Client</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deals->where('stage', 'won') as $d)
                        <tr>
                            <td style="font-weight: 600;">{{ $d->title }}</td>
                            <td>{{ $d->client->company ?? ($d->client->name ?? 'N/A') }}</td>
                            <td style="font-weight: 800; color: var(--primary);">${{ number_format($d->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;">No closed-won deals yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
