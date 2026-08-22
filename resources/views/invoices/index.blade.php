@extends('layouts.app')

@section('title', 'Invoices & Billing - AI CRM')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Invoices & Billing</h1>
        <p style="font-size: 13.5px; color: var(--text-muted);">Generate branded invoices, track payment status, sync website payments, and export print-ready PDFs.</p>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('createInvoiceBox').scrollIntoView({behavior: 'smooth'})">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Create Invoice</span>
        </button>
    </div>
</div>

<!-- 1. STAT METRICS BANNER -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 3px;">Total Invoiced</div>
            <div style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: var(--text-main);">₹{{ number_format($totalInvoiced, 2) }}</div>
        </div>
        <div style="width: 42px; height: 42px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 18px;">📑</div>
    </div>

    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 3px;">Paid Collections</div>
            <div style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #10b981;">₹{{ number_format($paidRevenue, 2) }}</div>
        </div>
        <div style="width: 42px; height: 42px; border-radius: 10px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 18px;">💰</div>
    </div>

    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 3px;">Pending Receivables</div>
            <div style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #f59e0b;">₹{{ number_format($pendingAmount, 2) }}</div>
        </div>
        <div style="width: 42px; height: 42px; border-radius: 10px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 18px;">⏳</div>
    </div>

    <div class="card card-p" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 3px;">Overdue Count</div>
            <div style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #ef4444;">{{ $overdueCount }}</div>
        </div>
        <div style="width: 42px; height: 42px; border-radius: 10px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 18px;">⚠️</div>
    </div>
</div>

<!-- 2. ACTIONS SPLIT: WEBSITE SYNC & CREATE INVOICE -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- TOOL A: AUTOMATED WEBSITE / HOSTINGER SYNC & PDF INGESTION -->
    <div class="card card-p" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span style="font-size: 20px;">🌐</span>
                <h2 style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: var(--text-main);">Hostinger & Website Payment Ingestion Engine</h2>
            </div>
            <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.45; margin-bottom: 16px;">
                Enter any website URL, Hostinger invoice folder link, or upload an invoice PDF directly. The AI ETL engine parses client details, payment amount & date, and updates revenue metrics & monthly forecasting in real-time!
            </p>

            <!-- 1. URL SCAN FORM -->
            <form action="{{ route('invoices.sync') }}" method="POST" style="margin-bottom: 16px;">
                @csrf
                <label class="form-label" style="font-weight: 700;">Hostinger Folder / Website URL</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" name="website_url" class="form-control" placeholder="https://my-hostinger-site.com/invoices/ or https://myshop.com" required style="flex: 1;">
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981, #059669); white-space: nowrap;">
                        <i data-lucide="zap" style="width: 15px; height: 15px;"></i>
                        <span>Scan & Ingest</span>
                    </button>
                </div>
            </form>

            <!-- 2. DIRECT PDF UPLOAD FORM -->
            <form action="{{ route('invoices.upload-pdf') }}" method="POST" enctype="multipart/form-data" style="background: var(--bg-surface-hover); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; margin-bottom: 14px;">
                @csrf
                <label class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">Or Upload Invoice PDF File Directly</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="file" name="invoice_pdf" accept=".pdf" class="form-control" required style="font-size: 12px; padding: 6px 10px;">
                    <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                        <i data-lucide="upload" style="width: 14px; height: 14px;"></i>
                        <span>Parse PDF</span>
                    </button>
                </div>
            </form>
        </div>

        <div style="background: var(--bg-body); border: 1px dashed var(--border-color); border-radius: 8px; padding: 10px 12px; font-size: 11.5px; color: var(--text-muted); word-break: break-all;">
            <strong>⚡ Live Payment Webhook Endpoint:</strong><br>
            <code>POST {{ url('/api/v1/payment-webhook') }}</code><br>
            <span style="font-size: 11px; color: var(--text-subtle);">Connect Hostinger PHP scripts, WooCommerce, or Shopify webhooks to automatically record every client payment!</span>
        </div>
    </div>

    <!-- TOOL B: MANUAL CREATE INVOICE FORM -->
    <div class="card card-p" id="createInvoiceBox">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <span style="font-size: 20px;">✍️</span>
            <h2 style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: var(--text-main);">Create Manual Invoice</h2>
        </div>
        <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 16px;">Generate a branded invoice for existing clients or enter direct billing amounts.</p>

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                <div>
                    <label class="form-label">Client *</label>
                    <select name="client_id" class="form-control" required>
                        <option value="">Select Client</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->name }} ({{ $c->company }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Invoice Number *</label>
                    <input type="text" name="invoice_number" class="form-control" value="INV-{{ date('Y') }}-{{ rand(1000, 9999) }}" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                <div>
                    <label class="form-label">Amount (₹ / $) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" min="0" required>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label class="form-label">Invoice Date *</label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i data-lucide="plus-circle" style="width: 16px; height: 16px;"></i>
                <span>Generate & Record Invoice</span>
            </button>
        </form>
    </div>
</div>

<!-- 3. INVOICES TABLE -->
<div class="card" style="overflow: hidden;">
    <div style="padding: 18px 22px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: var(--text-main);">All Invoices & Transactions</h2>
        <span class="badge badge-paid" style="padding: 5px 12px; font-size: 12px;">{{ $invoices->count() }} Invoices</span>
    </div>

    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client / Company</th>
                    <th>Amount</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                    <tr>
                        <td>
                            <a href="{{ route('invoices.pdf', $inv->id) }}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: none;">
                                {{ $inv->invoice_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $inv->client->name ?? 'Acme Customer' }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $inv->client->company ?? ($inv->client->email ?? 'Direct Client') }}</div>
                        </td>
                        <td>
                            <span style="font-family: var(--font-heading); font-weight: 800; font-size: 15px; color: var(--text-main);">
                                ₹{{ number_format($inv->amount, 2) }}
                            </span>
                        </td>
                        <td style="color: var(--text-muted);">{{ $inv->invoice_date ? $inv->invoice_date->format('M d, Y') : '—' }}</td>
                        <td>
                            @if($inv->is_overdue)
                                <span style="color: #ef4444; font-weight: 700;">{{ $inv->due_date->format('M d, Y') }} (Overdue)</span>
                            @else
                                <span style="color: var(--text-muted);">{{ $inv->due_date ? $inv->due_date->format('M d, Y') : 'Net 30' }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('invoices.toggle', $inv->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="badge {{ $inv->status === 'Paid' ? 'badge-paid' : 'badge-pending' }}" style="border:none; cursor:pointer;" title="Click to toggle status">
                                    {{ $inv->status }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('invoices.pdf', $inv->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Print or Download PDF">
                                    <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
                                    <span>PDF</span>
                                </a>

                                <a href="{{ route('invoices.edit', $inv->id) }}" class="btn btn-secondary btn-sm" title="Edit details">
                                    <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i>
                                </a>

                                <form action="{{ route('invoices.destroy', $inv->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" style="color: #dc2626;" type="submit" onclick="return confirm('Delete this invoice?')" title="Delete">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 45px 20px; color: var(--text-muted); font-size: 13.5px;">
                            No invoices recorded yet. Use the tools above to generate an invoice or sync payments from your website!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection