@extends('layouts.app')

@section('title', $client->name . ' - Client 360° Profile')

@section('content')
<style>
    .client-360-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .client-360-grid {
            grid-template-columns: 1fr;
        }
    }

    .timeline-list {
        position: relative;
        padding-left: 28px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .timeline-list::before {
        content: '';
        position: absolute;
        top: 8px;
        bottom: 8px;
        left: 10px;
        width: 2px;
        background-color: var(--border-color);
    }

    .timeline-item {
        position: relative;
    }

    .timeline-dot {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--bg-surface);
        border: 2px solid var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Back to Clients</span>
        </a>
        <h1 class="page-title" style="margin-bottom: 0;">{{ $client->name }}</h1>
    </div>

    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-secondary">
            <i data-lucide="edit" style="width: 15px; height: 15px;"></i>
            <span>Edit Details</span>
        </a>
        <form action="/clients/{{ $client->id }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete client {{ addslashes($client->name) }}?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary" style="color: var(--danger);" title="Delete Client">
                <i data-lucide="trash" style="width: 15px; height: 15px;"></i>
                <span>Delete</span>
            </button>
        </form>
    </div>
</div>

<div class="client-360-grid">
    <!-- Left Column: Contact & LTV Summary -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card card-p" style="text-align: center;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 16px;">
                {{ substr($client->name, 0, 2) }}
            </div>

            <h2 style="font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">{{ $client->name }}</h2>
            <div style="font-size: 13px; font-weight: 600; color: var(--primary); margin-bottom: 16px;">{{ $client->company ?: 'Independent Account' }}</div>

            <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 20px;">
                @if($client->email)
                    <a href="mailto:{{ $client->email }}" class="btn btn-secondary btn-sm" title="Send Email">
                        <i data-lucide="mail" style="width: 14px; height: 14px;"></i>
                    </a>
                @endif
                @if($client->phone)
                    <a href="tel:{{ $client->phone }}" class="btn btn-secondary btn-sm" title="Call">
                        <i data-lucide="phone" style="width: 14px; height: 14px;"></i>
                    </a>
                @endif
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 16px; display: flex; flex-direction: column; gap: 12px; text-align: left; font-size: 13px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Email:</span>
                    <span style="font-weight: 600;">{{ $client->email ?: 'N/A' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Phone:</span>
                    <span style="font-weight: 600;">{{ $client->phone ?: 'N/A' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Lifetime Value:</span>
                    <span style="font-weight: 800; color: var(--success);">${{ number_format($client->lifetime_value, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Log Activity Form -->
        <div class="card card-p">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-bottom: 14px;">Log Activity / Note</h3>
            <form action="{{ route('clients.activity', $client->id) }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <select name="type" class="form-control" style="font-size: 13px;">
                        <option value="note">📝 Note</option>
                        <option value="call">📞 Phone Call</option>
                        <option value="meeting">🤝 Meeting</option>
                        <option value="email">✉️ Email Thread</option>
                    </select>
                    <textarea name="description" class="form-control" rows="3" placeholder="Enter activity details..." required></textarea>
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">Post to Timeline</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Deals, Invoices & Activity Timeline -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Deals Table -->
        <div class="card" style="overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main);">Pipeline Deals ({{ $client->deals->count() }})</h3>
                <button type="button" class="btn btn-primary btn-sm" onclick="openQuickAddModal()">+ New Deal</button>
            </div>
            <div class="table-responsive">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Deal Title</th>
                            <th>Value</th>
                            <th>Stage</th>
                            <th>Win Probability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->deals as $d)
                            <tr>
                                <td style="font-weight: 600;">{{ $d->title }}</td>
                                <td style="font-weight: 700;">${{ number_format($d->amount, 0) }}</td>
                                <td><span class="badge badge-proposal">{{ ucfirst($d->stage) }}</span></td>
                                <td style="color: var(--primary); font-weight: 600;">{{ $d->probability }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">No deals linked yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="card" style="overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main);">Invoices ({{ $client->invoices->count() }})</h3>
            </div>
            <div class="table-responsive">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->invoices as $inv)
                            <tr>
                                <td style="font-weight: 700;">{{ $inv->invoice_number }}</td>
                                <td style="font-weight: 700;">${{ number_format($inv->amount, 0) }}</td>
                                <td>{{ $inv->invoice_date ? $inv->invoice_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $inv->status === 'Paid' ? 'badge-paid' : 'badge-pending' }}">{{ $inv->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.pdf', $inv->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                                        <i data-lucide="printer" style="width: 13px; height: 13px;"></i>
                                        <span>PDF</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">No invoices generated yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="card card-p">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px;">360° Interaction Timeline</h3>

            <div class="timeline-list">
                @forelse($client->activities as $act)
                    <div class="timeline-item">
                        <div class="timeline-dot">
                            @if($act->type === 'call')
                                <i data-lucide="phone" style="width: 12px; height: 12px;"></i>
                            @elseif($act->type === 'meeting')
                                <i data-lucide="users" style="width: 12px; height: 12px;"></i>
                            @elseif($act->type === 'email')
                                <i data-lucide="mail" style="width: 12px; height: 12px;"></i>
                            @else
                                <i data-lucide="file-text" style="width: 12px; height: 12px;"></i>
                            @endif
                        </div>
                        <div style="background: var(--bg-surface-hover); padding: 12px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 12px;">
                                <span style="font-weight: 700; text-transform: uppercase; color: var(--primary);">{{ $act->type }}</span>
                                <span style="color: var(--text-muted);">{{ $act->performed_at ? $act->performed_at->diffForHumans() : 'Recent' }}</span>
                            </div>
                            <div style="font-size: 13.5px; color: var(--text-main); line-height: 1.5;">{{ $act->description }}</div>
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-muted); font-size: 13px;">No timeline activities recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
