<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')
            ->latest()
            ->get();

        $clients = Client::all();

        return view('invoices.index', compact('invoices', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending',
        ]);

        Invoice::create([
            'client_id' => $request->client_id,
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('invoices.index');
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::all();

        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending',
        ]);

        $invoice->update([
            'client_id' => $request->client_id,
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}