<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Sale;
use App\Services\InvoiceIngestionService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $ingestionService;

    public function __construct(InvoiceIngestionService $ingestionService)
    {
        $this->ingestionService = $ingestionService;
    }

    public function index()
    {
        $invoices = Invoice::with('client')->latest()->get();
        $clients = Client::all();

        $totalInvoiced = $invoices->sum('amount');
        $paidRevenue = $invoices->where('status', 'Paid')->sum('amount');
        $pendingAmount = $invoices->where('status', 'Pending')->sum('amount');
        $overdueCount = $invoices->filter(fn($i) => $i->is_overdue)->count();

        return view('invoices.index', compact(
            'invoices',
            'clients',
            'totalInvoiced',
            'paidRevenue',
            'pendingAmount',
            'overdueCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending',
            'notes' => 'nullable|string',
        ]);

        $validated['items'] = [
            [
                'description' => 'Professional Software & Enterprise AI Services',
                'quantity' => 1,
                'unit_price' => (float)$validated['amount'],
                'total' => (float)$validated['amount'],
            ],
        ];

        $invoice = Invoice::create($validated);

        // Also record corresponding Sale if Paid
        if ($validated['status'] === 'Paid') {
            Sale::create([
                'client_id' => $validated['client_id'],
                'amount' => (float)$validated['amount'],
                'sale_date' => $validated['invoice_date'],
                'status' => 'Paid',
                'description' => 'Direct Invoice Sale: ' . $validated['invoice_number'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice #' . $invoice->invoice_number . ' generated successfully!');
    }

    /**
     * Automated Website / Hostinger Directory URL Ingestion.
     */
    public function syncFromUrl(Request $request)
    {
        $request->validate([
            'website_url' => 'required|url',
        ]);

        $result = $this->ingestionService->ingestFromUrl($request->input('website_url'));

        if ($result['success']) {
            return redirect()->route('invoices.index')->with('success', $result['message']);
        }

        return redirect()->route('invoices.index')->with('error', $result['message']);
    }

    /**
     * Direct PDF Invoice File Upload & Parsing.
     */
    public function uploadPdf(Request $request)
    {
        $request->validate([
            'invoice_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $file = $request->file('invoice_pdf');
        $result = $this->ingestionService->ingestFromUploadedPdf($file);

        if ($result['success']) {
            return redirect()->route('invoices.index')->with('success', $result['message']);
        }

        return redirect()->route('invoices.index')->with('error', $result['message'] ?? 'Failed to parse invoice PDF.');
    }

    /**
     * Live Payment Webhook Endpoint from external websites / Hostinger / Shopify.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        $result = $this->ingestionService->handleWebhook($payload);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function showPdf(Invoice $invoice)
    {
        $invoice->load('client');
        return view('invoices.pdf', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::all();
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }

    public function toggleStatus(Invoice $invoice)
    {
        $newStatus = $invoice->status === 'Paid' ? 'Pending' : 'Paid';
        $invoice->update(['status' => $newStatus]);

        return back()->with('success', "Invoice marked as {$newStatus}!");
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }
}