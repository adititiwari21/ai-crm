@extends('layouts.app')

@section('title', 'Clients Hub - CRM Pro')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Client Accounts</h1>
        <p class="page-subtitle">Manage customer relationships, lifetime value, and active opportunities.</p>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="button" class="btn btn-primary" onclick="openQuickAddModal()">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Add Client</span>
        </button>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Company</th>
                    <th>Contact Info</th>
                    <th>Open Deals</th>
                    <th>Lifetime Revenue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $c)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                    {{ substr($c->name, 0, 2) }}
                                </div>
                                <div>
                                    <a href="{{ route('clients.show', $c->id) }}" style="font-weight: 700; color: var(--text-main); text-decoration: none;">{{ $c->name }}</a>
                                    <div style="font-size: 11.5px; color: var(--text-muted);">Joined {{ $c->created_at ? $c->created_at->format('M Y') : 'Recent' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 600; color: var(--text-main);">{{ $c->company ?: 'N/A' }}</td>
                        <td>
                            <div style="font-size: 13px; color: var(--text-main);">{{ $c->email }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $c->phone ?: 'No phone' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ $c->deals->count() }} Deals (${{ number_format($c->open_deals_value, 0) }})</span>
                        </td>
                        <td style="font-family: var(--font-heading); font-weight: 800; color: var(--success); font-size: 15px;">
                            ${{ number_format($c->lifetime_value, 0) }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('clients.show', $c->id) }}" class="btn btn-secondary btn-sm" title="View 360° Profile">
                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('clients.edit', $c->id) }}" class="btn btn-secondary btn-sm" title="Edit Client">
                                    <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No clients found. Click "Add Client" to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection