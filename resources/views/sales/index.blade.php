@extends('layouts.app')

@section('title', 'Sales & Transactions - AI CRM')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Sales Transactions</h1>
        <p style="font-size: 13.5px; color: var(--text-muted);">Record, track, and manage all client sales revenue and payment transactions.</p>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addSaleCard').scrollIntoView({behavior: 'smooth'})">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Record New Sale</span>
        </button>
    </div>
</div>

<!-- 1. STATS BANNER -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Total Sales Revenue</div>
            <div style="font-family: var(--font-heading); font-size: 24px; font-weight: 800; color: #10b981;">₹{{ number_format($sales->where('status', 'Paid')->sum('amount'), 2) }}</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 20px;">💰</div>
    </div>

    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Recorded Transactions</div>
            <div style="font-family: var(--font-heading); font-size: 24px; font-weight: 800; color: var(--text-main);">{{ $sales->count() }}</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 20px;">🛒</div>
    </div>

    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Pending Collections</div>
            <div style="font-family: var(--font-heading); font-size: 24px; font-weight: 800; color: #f59e0b;">₹{{ number_format($sales->where('status', 'Pending')->sum('amount'), 2) }}</div>
        </div>
        <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 20px;">⏳</div>
    </div>
</div>

<!-- 2. ADD SALE CARD -->
<div class="card card-p" id="addSaleCard" style="margin-bottom: 24px;">
    <div style="margin-bottom: 18px;">
        <h2 style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">Record New Sale Transaction</h2>
        <p style="font-size: 12.5px; color: var(--text-muted);">Enter transaction details to add to financial records and update dashboard metrics.</p>
    </div>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 16px;">
            <div>
                <label class="form-label">Client *</label>
                <select name="client_id" class="form-control" required>
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->name }} ({{ $client->company }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Amount (₹ / $) *</label>
                <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" min="0" required>
            </div>

            <div>
                <label class="form-label">Sale Date *</label>
                <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div>
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                <span>+ Record Sale</span>
            </button>
        </div>
    </form>
</div>

<!-- 3. SALES TABLE -->
<div class="card" style="overflow: hidden;">
    <div style="padding: 18px 22px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: var(--text-main);">All Sales Transactions</h2>
        <span class="badge badge-paid" style="padding: 5px 12px; font-size: 12px;">{{ $sales->count() }} Recorded Sales</span>
    </div>

    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Amount</th>
                    <th>Sale Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $sale->client->name ?? 'Direct Client' }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $sale->client->company ?? ($sale->client->email ?? '') }}</div>
                        </td>
                        <td>
                            <span style="font-family: var(--font-heading); font-weight: 800; color: #10b981; font-size: 15px;">
                                ₹{{ number_format($sale->amount, 2) }}
                            </span>
                        </td>
                        <td style="color: var(--text-muted);">{{ $sale->sale_date ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $sale->status === 'Paid' ? 'badge-paid' : 'badge-pending' }}">
                                {{ $sale->status }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                @if(Route::has('sales.edit'))
                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                                        <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i>
                                    </a>
                                @endif

                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" style="color: #dc2626;" type="submit" onclick="return confirm('Delete this sale record?')" title="Delete">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 45px 20px; color: var(--text-muted); font-size: 13.5px;">
                            No sales records available yet. Use the form above to record your first transaction.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection