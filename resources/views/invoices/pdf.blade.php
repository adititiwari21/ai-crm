<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .invoice-sheet {
            background-color: #ffffff;
            width: 800px;
            min-height: 1000px;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .invoice-sheet {
                box-shadow: none;
                padding: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }

        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 30px;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 800;
        }

        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }

        .inv-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .meta-col-title {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .meta-val-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .meta-val-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            padding: 12px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13.5px;
            color: #1e293b;
        }

        .summary-wrap {
            margin-left: auto;
            width: 300px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 40px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13.5px;
            color: #64748b;
        }

        .total-row {
            border-top: 2px solid #0f172a;
            padding-top: 10px;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-paid {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #b45309;
        }

        .inv-footer {
            margin-top: auto;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-sheet">
        <!-- Print / Actions Bar -->
        <div class="no-print" style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px;">
            <button onclick="window.print()" style="padding: 10px 18px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                🖨️ Print / Download PDF
            </button>
        </div>

        <!-- Header -->
        <div class="inv-header">
            <div class="brand-box">
                <div class="brand-logo">CP</div>
                <div>
                    <div class="brand-name">CRM Pro Enterprises</div>
                    <div style="font-size: 12px; color: #64748b;">AI-Native Enterprise Operations</div>
                </div>
            </div>

            <div style="text-align: right;">
                <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">INVOICE</h1>
                <div style="font-size: 14px; font-weight: 700; color: #4f46e5;">#{{ $invoice->invoice_number }}</div>
                <div style="margin-top: 8px;">
                    <span class="status-badge {{ $invoice->status === 'Paid' ? 'badge-paid' : 'badge-pending' }}">
                        {{ strtoupper($invoice->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="inv-meta-grid">
            <div>
                <div class="meta-col-title">Billed To:</div>
                <div class="meta-val-name">{{ $invoice->client->company ?? ($invoice->client->name ?? 'Acme Inc.') }}</div>
                <div class="meta-val-text">Attn: {{ $invoice->client->name ?? 'Accounts Payable' }}</div>
                <div class="meta-val-text">{{ $invoice->client->email ?? 'billing@acme.com' }}</div>
                <div class="meta-val-text">{{ $invoice->client->phone ?? '+1 (555) 000-0000' }}</div>
            </div>

            <div style="text-align: right;">
                <div class="meta-col-title">Invoice Details:</div>
                <div class="meta-val-text"><strong>Issue Date:</strong> {{ $invoice->invoice_date ? $invoice->invoice_date->format('M d, Y') : 'N/A' }}</div>
                <div class="meta-val-text"><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'Net 30' }}</div>
                <div class="meta-val-text"><strong>Currency:</strong> USD ($)</div>
            </div>
        </div>

        <!-- Line Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($invoice->items) && is_array($invoice->items))
                    @foreach($invoice->items as $item)
                        <tr>
                            <td style="font-weight: 600;">{{ $item['description'] ?? 'Product Service' }}</td>
                            <td style="text-align: center;">{{ $item['quantity'] ?? 1 }}</td>
                            <td style="text-align: right;">${{ number_format($item['unit_price'] ?? $invoice->amount, 2) }}</td>
                            <td style="text-align: right; font-weight: 700;">${{ number_format($item['total'] ?? $invoice->amount, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="font-weight: 600;">Enterprise AI Copilot & CRM Platform Subscription</td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
                        <td style="text-align: right; font-weight: 700;">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Summary Calculation -->
        <div class="summary-wrap">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($invoice->amount, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Tax (0%):</span>
                <span>$0.00</span>
            </div>
            <div class="summary-row total-row">
                <span>Total Due:</span>
                <span>${{ number_format($invoice->amount, 2) }}</span>
            </div>
        </div>

        <!-- Payment Terms & Notes -->
        <div style="background: #f8fafc; border-radius: 8px; padding: 16px; margin-bottom: 20px; font-size: 12.5px; color: #475569; line-height: 1.5;">
            <strong>Payment Instructions:</strong><br>
            Please send wire transfers to: <strong>Silicon Valley Bank</strong> | Routing: <strong>121000358</strong> | Account: <strong>8892019481</strong><br>
            {{ $invoice->notes ?: 'Thank you for choosing CRM Pro!' }}
        </div>

        <!-- Footer -->
        <div class="inv-footer">
            CRM Pro Inc. • 500 Howard Street, Suite 400, San Francisco, CA 94105 • support@crmpro.ai
        </div>
    </div>
</body>
</html>
