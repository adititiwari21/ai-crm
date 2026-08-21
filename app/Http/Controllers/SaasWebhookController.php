<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientActivity;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SaasWebhookController extends Controller
{
    /**
     * Display SaaS Webhook & Purchase Integration Hub with Live Simulator
     */
    public function index()
    {
        $recentPurchases = Sale::with('client')->latest()->take(10)->get();
        $recentInvoices = Invoice::with('client')->where('status', 'Paid')->latest()->take(10)->get();

        return view('saas.integrations', compact('recentPurchases', 'recentInvoices'));
    }

    /**
     * Webhook Endpoint to receive SaaS Purchases & Monthly Subscriptions automatically.
     * Can be called from Stripe, Razorpay, LemonSqueezy, or custom website backend.
     */
    public function handlePurchaseWebhook(Request $request)
    {
        // 1. Validate payload
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'plan_name' => 'nullable|string|max:255',
            'billing_cycle' => 'nullable|string|in:monthly,annual,one-time',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'payment_gateway' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        $email = trim($validated['email']);
        $name = !empty($validated['name']) ? trim($validated['name']) : ucfirst(explode('@', $email)[0]);
        $company = !empty($validated['company']) ? trim($validated['company']) : ($name . ' Enterprise');
        $planName = !empty($validated['plan_name']) ? trim($validated['plan_name']) : 'SaaS Pro Monthly Subscription';
        $billingCycle = !empty($validated['billing_cycle']) ? trim($validated['billing_cycle']) : 'monthly';
        $amount = (float) $validated['amount'];
        $currency = !empty($validated['currency']) ? strtoupper(trim($validated['currency'])) : 'USD';
        $gateway = !empty($validated['payment_gateway']) ? trim($validated['payment_gateway']) : 'Website Checkout';
        $txnId = !empty($validated['transaction_id']) ? trim($validated['transaction_id']) : ('TXN-' . strtoupper(uniqid()));

        // ==========================================================
        // 1. CREATE OR FIND CLIENT IN CRM
        // ==========================================================
        $client = Client::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => $validated['phone'] ?? null,
                'company' => $company,
            ]
        );

        // Update company/phone if previously missing
        if (empty($client->company) && !empty($company)) {
            $client->update(['company' => $company]);
        }

        // ==========================================================
        // 2. CREATE AUTOMATED SALE RECORD
        // ==========================================================
        $sale = Sale::create([
            'client_id' => $client->id,
            'amount' => $amount,
            'sale_date' => Carbon::now()->toDateString(),
            'status' => 'Paid',
        ]);

        // ==========================================================
        // 3. GENERATE ITEMIZED PAID INVOICE
        // ==========================================================
        $invoiceNumber = 'INV-' . date('Y') . '-' . strtoupper(substr(md5($txnId . time()), 0, 5));
        
        $invoice = Invoice::create([
            'client_id' => $client->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'invoice_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->toDateString(),
            'status' => 'Paid',
            'items' => [
                [
                    'description' => "{$planName} (" . ucfirst($billingCycle) . " Plan)",
                    'quantity' => 1,
                    'unit_price' => $amount,
                    'total' => $amount,
                ],
            ],
            'tax_rate' => 0.0,
            'discount' => 0.0,
            'notes' => "Payment automatically processed via {$gateway}. Transaction ID: {$txnId}",
        ]);

        // ==========================================================
        // 4. CREATE CLOSED-WON PIPELINE DEAL
        // ==========================================================
        $deal = Deal::create([
            'client_id' => $client->id,
            'title' => "{$company} - {$planName}",
            'amount' => $amount,
            'stage' => 'won',
            'probability' => 100,
            'expected_close_date' => Carbon::now()->toDateString(),
            'notes' => "Automated SaaS subscription purchase via {$gateway}. Billing: " . ucfirst($billingCycle),
        ]);

        // ==========================================================
        // 5. LOG ACTIVITY ON CLIENT 360 TIMELINE
        // ==========================================================
        ClientActivity::create([
            'client_id' => $client->id,
            'type' => 'note',
            'description' => "💳 New SaaS Subscription Purchased: {$planName} ({$currency} {$amount}/{$billingCycle}) via {$gateway}. Txn: {$txnId}",
            'performed_at' => Carbon::now(),
        ]);

        // ==========================================================
        // 6. IF LEAD EXISTED IN LEADS HUB, MARK AS CONVERTED
        // ==========================================================
        $lead = UserDetail::where('email', $email)->first();
        if ($lead) {
            $lead->update(['status' => 'Converted']);
        }

        Log::info("SaaS Subscription ingested automatically for {$email} ({$planName} - \${$amount})");

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'SaaS Subscription ingested into CRM successfully!',
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'company' => $client->company,
                ],
                'sale_id' => $sale->id,
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoiceNumber,
                'deal_id' => $deal->id,
                'amount' => $amount,
                'plan' => $planName,
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', "🎉 SaaS Purchase Ingested! Client '{$client->name}' (${$amount}) added to CRM with Paid Invoice and Closed-Won Deal!");
    }
}
