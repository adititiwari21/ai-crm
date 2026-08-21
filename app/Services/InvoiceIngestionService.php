<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceIngestionService
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Ingest and parse payment / invoice data from a Website or Hostinger URL.
     */
    public function ingestFromUrl(string $url): array
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/json,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch the URL (HTTP ' . $response->status() . '). Please verify the link is publicly accessible.',
                    'count' => 0,
                ];
            }

            $content = $response->body();
            $invoicesExtracted = $this->extractInvoicesFromContent($content, $url);

            if (empty($invoicesExtracted)) {
                return [
                    'success' => false,
                    'message' => 'No payment or invoice data could be found on the provided page.',
                    'count' => 0,
                ];
            }

            $savedCount = 0;
            foreach ($invoicesExtracted as $item) {
                $saved = $this->saveIngestedTransaction($item, 'website_sync');
                if ($saved) {
                    $savedCount++;
                }
            }

            return [
                'success' => true,
                'message' => "Successfully ingested {$savedCount} transaction(s) from website!",
                'count' => $savedCount,
                'items' => $invoicesExtracted,
            ];
        } catch (\Exception $e) {
            Log::error('Website Ingestion Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error connecting to site: ' . $e->getMessage(),
                'count' => 0,
            ];
        }
    }

    /**
     * Process an incoming Payment Webhook from any external site / Hostinger PHP script.
     */
    public function handleWebhook(array $payload): array
    {
        // Support standardized and popular gateway payload formats (Stripe, Razorpay, WooCommerce, Custom)
        $clientName = $payload['client_name'] ?? $payload['customer_name'] ?? $payload['name'] ?? $payload['billing']['first_name'] ?? 'Online Customer';
        $clientEmail = $payload['client_email'] ?? $payload['email'] ?? $payload['customer_email'] ?? $payload['billing']['email'] ?? null;
        $clientCompany = $payload['company'] ?? $payload['client_company'] ?? 'Web Customer';
        
        $amount = (float) ($payload['amount'] ?? $payload['total'] ?? $payload['price'] ?? 0);
        if (isset($payload['currency']) && strtolower($payload['currency']) === 'inr' && isset($payload['amount_in_cents'])) {
            $amount = $payload['amount_in_cents'] / 100;
        }

        $invoiceNumber = $payload['invoice_number'] ?? $payload['order_id'] ?? $payload['transaction_id'] ?? ('INV-' . strtoupper(Str::random(8)));
        $status = strtolower($payload['status'] ?? 'paid') === 'pending' ? 'Pending' : 'Paid';
        $date = isset($payload['date']) ? date('Y-m-d', strtotime($payload['date'])) : date('Y-m-d');

        $transaction = [
            'client_name' => $clientName,
            'client_email' => $clientEmail,
            'company' => $clientCompany,
            'amount' => $amount,
            'invoice_number' => $invoiceNumber,
            'status' => $status,
            'date' => $date,
            'notes' => 'Ingested via automated payment webhook from ' . ($payload['source'] ?? 'External Site'),
        ];

        $saved = $this->saveIngestedTransaction($transaction, 'webhook');

        return [
            'success' => (bool)$saved,
            'invoice' => $saved,
            'message' => $saved ? 'Webhook payment recorded successfully!' : 'Failed to save transaction',
        ];
    }

    /**
     * Save/Upsert ingested transaction into Client, Invoice, and Sale models.
     */
    public function saveIngestedTransaction(array $data, string $source = 'system'): ?Invoice
    {
        if (empty($data['amount']) || $data['amount'] <= 0) {
            return null;
        }

        // 1. Find or create Client
        $client = null;
        if (!empty($data['client_email'])) {
            $client = Client::where('email', $data['client_email'])->first();
        }
        if (!$client && !empty($data['client_name'])) {
            $client = Client::where('name', $data['client_name'])->first();
        }

        if (!$client) {
            $client = Client::create([
                'name' => $data['client_name'] ?? 'Valued Customer',
                'company' => $data['company'] ?? 'Individual Client',
                'email' => $data['client_email'] ?? (Str::slug($data['client_name'] ?? 'client') . '@customer.io'),
                'phone' => $data['phone'] ?? '+91 (555) 019-2831',
                'status' => 'active',
                'notes' => 'Customer auto-created via ' . $source,
            ]);
        }

        // 2. Format Invoice Number
        $invoiceNum = $data['invoice_number'] ?? ('INV-' . date('Y') . '-' . rand(1000, 9999));

        // Check if invoice already exists to avoid duplicates
        $existingInvoice = Invoice::where('invoice_number', $invoiceNum)->first();
        if ($existingInvoice) {
            return $existingInvoice;
        }

        // 3. Create Invoice
        $invoice = Invoice::create([
            'client_id' => $client->id,
            'invoice_number' => $invoiceNum,
            'amount' => (float)$data['amount'],
            'invoice_date' => $data['date'] ?? date('Y-m-d'),
            'due_date' => $data['due_date'] ?? date('Y-m-d', strtotime('+30 days')),
            'status' => $data['status'] ?? 'Paid',
            'notes' => $data['notes'] ?? ('Automated ingestion via ' . $source),
            'items' => [
                [
                    'description' => $data['description'] ?? 'E-Commerce / Enterprise Product & Service Payment',
                    'quantity' => 1,
                    'unit_price' => (float)$data['amount'],
                    'total' => (float)$data['amount'],
                ]
            ],
        ]);

        // 4. Create Sale Record for Financial Accounting
        Sale::create([
            'client_id' => $client->id,
            'amount' => (float)$data['amount'],
            'sale_date' => $data['date'] ?? date('Y-m-d'),
            'status' => $data['status'] ?? 'Paid',
            'description' => 'Sale from ' . $invoiceNum . ' (' . $source . ')',
        ]);

        return $invoice;
    }

    /**
     * Intelligent parsing using AI and regex fallback.
     */
    protected function extractInvoicesFromContent(string $html, string $sourceUrl): array
    {
        // 1. If it's pure JSON API response
        $decodedJson = json_decode($html, true);
        if (is_array($decodedJson)) {
            $extracted = [];
            $records = isset($decodedJson['data']) ? $decodedJson['data'] : (isset($decodedJson['invoices']) ? $decodedJson['invoices'] : (isset($decodedJson['orders']) ? $decodedJson['orders'] : [$decodedJson]));
            
            foreach ($records as $rec) {
                if (is_array($rec)) {
                    $extracted[] = [
                        'client_name' => $rec['client_name'] ?? $rec['name'] ?? $rec['customer'] ?? 'Customer',
                        'client_email' => $rec['email'] ?? null,
                        'company' => $rec['company'] ?? 'Direct Client',
                        'amount' => (float)($rec['amount'] ?? $rec['total'] ?? $rec['price'] ?? 0),
                        'invoice_number' => $rec['invoice_number'] ?? $rec['order_id'] ?? ('INV-' . rand(1000, 9999)),
                        'status' => strtolower($rec['status'] ?? 'paid') === 'pending' ? 'Pending' : 'Paid',
                        'date' => $rec['date'] ?? date('Y-m-d'),
                    ];
                }
            }
            if (!empty($extracted)) {
                return $extracted;
            }
        }

        // 2. Strip HTML tags to extract raw text
        $plainText = strip_tags(preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html));
        $plainText = preg_replace('/\s+/', ' ', $plainText);
        $plainTextSnippet = substr($plainText, 0, 4000);

        // 3. Try Gemini AI Extraction
        $aiPrompt = "You are an expert financial and invoice ETL parser. Analyze the following text extracted from a website/invoice directory ({$sourceUrl}) and extract all payment/invoice records.
Return ONLY a valid JSON array of objects with keys:
client_name (string), client_email (string or null), company (string), amount (number, required), invoice_number (string), status ('Paid' or 'Pending'), date ('YYYY-MM-DD').
If no explicit invoice number exists, generate a realistic one like INV-" . date('Y') . "-XXX.

Content:
{$plainTextSnippet}

JSON ARRAY ONLY:";

        try {
            $aiResponse = $this->gemini->askCrmCopilot($aiPrompt);
            if (!empty($aiResponse)) {
                // Look for JSON array in AI response
                if (preg_match('/\[\s*\{.*\}\s*\]/s', $aiResponse, $matches)) {
                    $parsed = json_decode($matches[0], true);
                    if (is_array($parsed) && count($parsed) > 0) {
                        return $parsed;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('AI Invoice parsing fallback: ' . $e->getMessage());
        }

        // 4. Regex Pattern Fallback (Scan for Currency symbols ₹, $, USD, INR and amounts)
        $extracted = [];
        if (preg_match_all('/(?:₹|\$|USD|INR|EUR|GBP)\s*([\d,]+(?:\.\d{2})?)/i', $plainTextSnippet, $amountMatches)) {
            $domain = parse_url($sourceUrl, PHP_URL_HOST) ?? 'Website Customer';
            $uniqueAmounts = array_unique(array_slice($amountMatches[1], 0, 3));

            foreach ($uniqueAmounts as $idx => $amtStr) {
                $cleanAmount = (float)str_replace(',', '', $amtStr);
                if ($cleanAmount > 0) {
                    $extracted[] = [
                        'client_name' => 'Client from ' . $domain,
                        'client_email' => 'client' . ($idx + 1) . '@' . $domain,
                        'company' => ucfirst(explode('.', $domain)[0]),
                        'amount' => $cleanAmount,
                        'invoice_number' => 'INV-' . date('Y') . '-' . rand(1000, 9999),
                        'status' => 'Paid',
                        'date' => date('Y-m-d'),
                        'notes' => 'Imported automatically from ' . $sourceUrl,
                    ];
                }
            }
        }

        return $extracted;
    }
}
