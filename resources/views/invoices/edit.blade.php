@extends('layouts.app')

@section('title', 'Edit Invoice - ' . $invoice->invoice_number)

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Back</span>
        </a>
        <h1 class="page-title" style="margin-bottom: 0;">Edit Invoice Details</h1>
    </div>

    <div class="card card-p">
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Client *</label>
                        <select name="client_id" class="form-control" required>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ $invoice->client_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->company }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Invoice Number *</label>
                        <input type="text" name="invoice_number" class="form-control" value="{{ $invoice->invoice_number }}" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Amount ($) *</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $invoice->amount }}" required>
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="Pending" {{ $invoice->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ $invoice->status === 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Invoice Date *</label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '' }}" required>
                    </div>
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary">Save Invoice</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
