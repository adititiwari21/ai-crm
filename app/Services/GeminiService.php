<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sale;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY') ?: null;
        $this->model = env('GEMINI_MODEL', 'gemini-2.5-flash');
    }

    /**
     * Ask AI a CRM-aware question with full live database context.
     */
    public function askCrmCopilot(string $prompt, array $conversationHistory = []): string
    {
        // 1. Gather live CRM metrics & context
        $context = $this->buildLiveCrmContext();

        // 2. If API Key is configured, make real Gemini API call
        if ($this->apiKey) {
            try {
                $systemPrompt = "You are CRM Pro AI, an elite AI Executive Copilot integrated inside an enterprise CRM.
You have real-time access to the company's live CRM database. Always provide direct, razor-sharp, actionable business insights with exact numbers, formatted with clean Markdown bullet points and bold key figures.

CURRENT LIVE CRM DATABASE SNAPSHOT:
{$context}

When answering:
- Reference real clients, deal names, invoice numbers, and monetary figures from the snapshot.
- If asked to analyze pipeline or revenue, calculate ratios and give proactive sales recommendations.
- Keep tone professional, concise, and executive-ready.";

                $messages = [];
                // Add conversation history
                foreach ($conversationHistory as $msg) {
                    $messages[] = [
                        'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                        'parts' => [['text' => $msg['content']]],
                    ];
                }

                // Add current prompt
                $messages[] = [
                    'role' => 'user',
                    'parts' => [['text' => $prompt]],
                ];

                $response = Http::timeout(6)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("{$this->baseUrl}{$this->model}:generateContent?key={$this->apiKey}", [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => $messages,
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 1200,
                    ],
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($reply) {
                        return trim($reply);
                    }
                }

                Log::warning('Gemini API returned error, falling back to local CRM reasoning engine: ' . $response->body());
            } catch (\Throwable $e) {
                Log::error('Gemini API call failed: ' . $e->getMessage());
            }
        }

        // 3. High-intelligence Local Fallback Engine (Uses Live Database Data)
        return $this->generateLocalCrmReasoning($prompt);
    }

    /**
     * Analyze a scraped company website and generate structured AI intelligence.
     */
    public function analyzeCompanyWebsite(string $url, string $title, string $description, string $headings): array
    {
        if ($this->apiKey) {
            try {
                $prompt = "You are an expert B2B Sales Intelligence Analyst. Analyze this scraped company website data and output ONLY valid JSON with no markdown formatting.
URL: {$url}
Title: {$title}
Description: {$description}
Headings: {$headings}

Return exact JSON format:
{
  \"ai_summary\": \"2-3 sentence executive summary of what this company does and their value proposition.\",
  \"industry\": \"Primary Industry/Sector (e.g. FinTech, Cloud Infrastructure, HealthTech, E-commerce)\",
  \"target_audience\": \"Primary buyer persona (e.g. CTOs, VP of Sales, Enterprise Security Teams)\",
  \"tech_stack\": \"Estimated tech stack based on headings and keywords (comma separated, e.g. React, Node.js, AWS, Python)\",
  \"lead_score\": 85, // Integer from 1 to 100 based on enterprise fit and modernization
  \"generated_pitch\": \"A compelling, 3-paragraph personalized cold outreach email tailored to their pain points, highlighting how our CRM/AI solutions can help them scale.\"
}";

                $response = Http::timeout(6)->post("{$this->baseUrl}{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.3,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

                if ($response->successful()) {
                    $jsonText = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $decoded = json_decode($jsonText, true);
                    if ($decoded && isset($decoded['ai_summary'])) {
                        return $decoded;
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Gemini company analysis failed: ' . $e->getMessage());
            }
        }

        // Algorithmic intelligent extraction fallback
        $domain = parse_url($url, PHP_URL_HOST) ?? $url;
        $cleanDomain = str_replace('www.', '', $domain);
        $companyName = ucfirst(explode('.', $cleanDomain)[0]);

        $score = 75;
        if (str_contains(strtolower($title . $headings), 'api') || str_contains(strtolower($title . $headings), 'cloud') || str_contains(strtolower($title . $headings), 'ai')) {
            $score += 15;
        }

        return [
            'ai_summary' => "{$companyName} ({$cleanDomain}) is an innovative organization offering {$title}. They focus on delivering high-impact solutions with a modern digital infrastructure.",
            'industry' => $this->detectIndustry($title, $description, $headings),
            'target_audience' => 'Engineering Directors, Operations Managers, and Business Executives',
            'tech_stack' => $this->detectTechStack($headings . ' ' . $description),
            'lead_score' => min(98, max(45, $score)),
            'generated_pitch' => "Hi {$companyName} Team,\n\nI came across {$cleanDomain} and was impressed by your work in {$title}.\n\nAs your customer operations scale, having an AI-powered CRM with automated lead intelligence and real-time pipeline analytics can significantly accelerate deal velocity.\n\nWould you be open to a 10-minute discovery call this week to see how we help teams like yours streamline revenue workflows?\n\nBest regards,\nCRM Pro Sales Team",
        ];
    }

    /**
     * Build comprehensive live database context string for AI.
     */
    protected function buildLiveCrmContext(): string
    {
        $clients = Client::with(['deals', 'invoices'])->get();
        $deals = Deal::with('client')->get();
        $invoices = Invoice::with('client')->get();
        $products = Product::all();
        $leads = UserDetail::latest()->take(5)->get();

        $totalRevenue = $invoices->where('status', 'Paid')->sum('amount');
        $pendingRevenue = $invoices->where('status', 'Pending')->sum('amount');
        $pipelineValue = $deals->whereNotIn('stage', ['won', 'lost'])->sum('amount');
        $wonValue = $deals->where('stage', 'won')->sum('amount');

        $dealsByStage = $deals->groupBy('stage')->map->count();

        $lines = [];
        $lines[] = "=== FINANCIAL OVERVIEW ===";
        $lines[] = "- Total Realized Revenue (Paid Invoices): $" . number_format($totalRevenue, 2);
        $lines[] = "- Pending Invoices Amount: $" . number_format($pendingRevenue, 2) . " across " . $invoices->where('status', 'Pending')->count() . " invoices";
        $lines[] = "- Active Pipeline Value: $" . number_format($pipelineValue, 2);
        $lines[] = "- Closed Won Deals Value: $" . number_format($wonValue, 2);
        $lines[] = "- Total Clients Count: " . $clients->count();
        $lines[] = "- Total Active Deals Count: " . $deals->count();

        $lines[] = "\n=== PIPELINE STAGE BREAKDOWN ===";
        foreach ($dealsByStage as $stage => $count) {
            $stageSum = $deals->where('stage', $stage)->sum('amount');
            $lines[] = "- Stage '" . ucfirst($stage) . "': {$count} deals (Total: $" . number_format($stageSum, 2) . ")";
        }

        $lines[] = "\n=== RECENT HIGH-VALUE DEALS ===";
        foreach ($deals->sortByDesc('amount')->take(6) as $d) {
            $lines[] = "- Deal: '{$d->title}' | Client: " . ($d->client->name ?? 'Unknown') . " ({$d->client->company}) | Value: $" . number_format($d->amount, 2) . " | Stage: {$d->stage} | Probability: {$d->probability}% | Close Date: " . ($d->expected_close_date ? $d->expected_close_date->format('Y-m-d') : 'N/A');
        }

        $lines[] = "\n=== PENDING / OVERDUE INVOICES ===";
        foreach ($invoices->where('status', 'Pending') as $inv) {
            $overdue = ($inv->due_date && $inv->due_date->isPast()) ? ' [OVERDUE!]' : '';
            $lines[] = "- Invoice #{$inv->invoice_number} | Client: " . ($inv->client->name ?? 'Unknown') . " | Amount: $" . number_format($inv->amount, 2) . " | Due: " . ($inv->due_date ? $inv->due_date->format('Y-m-d') : 'N/A') . "{$overdue}";
        }

        $lines[] = "\n=== CLIENTS SUMMARY ===";
        foreach ($clients->take(8) as $c) {
            $lines[] = "- Client: {$c->name} | Company: {$c->company} | Email: {$c->email} | Lifetime Value: $" . number_format($c->lifetime_value, 2);
        }

        $lines[] = "\n=== HOT LEADS ===";
        foreach ($leads as $l) {
            $lines[] = "- Lead: {$l->name} | Company: {$l->company} | Website: {$l->website} | Score: {$l->lead_score}/100 | Industry: {$l->industry}";
        }

        return implode("\n", $lines);
    }

    /**
     * Generate intelligent data-driven reasoning response using live Eloquent models.
     */
    protected function generateLocalCrmReasoning(string $q): string
    {
        $q = strtolower($q);

        $totalRevenue = Invoice::where('status', 'Paid')->sum('amount');
        $pendingRevenue = Invoice::where('status', 'Pending')->sum('amount');
        $pendingInvoices = Invoice::with('client')->where('status', 'Pending')->get();
        $totalClients = Client::count();
        $deals = Deal::with('client')->get();
        $openDeals = $deals->whereNotIn('stage', ['won', 'lost']);
        $wonDeals = $deals->where('stage', 'won');
        $leads = UserDetail::all();

        // 1. Revenue & Financials
        if (str_contains($q, 'revenue') || str_contains($q, 'sales') || str_contains($q, 'income') || str_contains($q, 'money') || str_contains($q, 'financial') || str_contains($q, 'paisa') || str_contains($q, 'kamai') || str_contains($q, 'kitna') || str_contains($q, 'earning') || str_contains($q, 'cash') || str_contains($q, 'profit') || str_contains($q, 'forecast')) {
            $wonSum = $wonDeals->sum('amount');
            $currentMonthRevenue = Invoice::where('status', 'Paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
            $dayOfMonth = max(1, now()->day);
            $daysInMonth = now()->daysInMonth;
            $monthlyRunRate = ($currentMonthRevenue / $dayOfMonth) * $daysInMonth;

            return "### 💰 Live Financial & Revenue Intelligence\n\n" .
                "- **Total Collected Revenue (Paid Invoices):** `$" . number_format($totalRevenue, 2) . "`\n" .
                "- **Current Month Collected Sales:** `$" . number_format($currentMonthRevenue, 2) . "`\n" .
                "- **Projected 30-Day Sales Forecast:** `$" . number_format($monthlyRunRate, 2) . "`\n" .
                "- **Pending Outstanding Receivables:** `$" . number_format($pendingRevenue, 2) . "` across **{$pendingInvoices->count()} pending invoices**\n" .
                "- **Closed-Won Bookings Value:** `$" . number_format($wonSum, 2) . "`\n" .
                "- **Active Pipeline Value:** `$" . number_format($openDeals->sum('amount'), 2) . "` across **{$openDeals->count()} active deals**\n\n" .
                "💡 **Executive Recommendation:** Collect the **" . $pendingInvoices->count() . " pending invoices** to immediately unlock `$" . number_format($pendingRevenue, 2) . "` in realized cash flow.";
        }

        // 2. Deals & Pipeline
        if (str_contains($q, 'deal') || str_contains($q, 'pipeline') || str_contains($q, 'opportunity') || str_contains($q, 'opportunities') || str_contains($q, 'closing')) {
            $stages = $deals->groupBy('stage');
            $topDeals = $deals->sortByDesc('amount')->take(3);
            
            $text = "### 💼 Pipeline & Opportunity Breakdown\n\n";
            $text .= "You currently have **{$deals->count()} total deals** valued at **$" . number_format($deals->sum('amount'), 2) . "** across all stages:\n\n";
            foreach ($stages as $stage => $items) {
                $text .= "- **" . ucfirst($stage) . ":** {$items->count()} deals (`$" . number_format($items->sum('amount'), 2) . "`)\n";
            }
            $text .= "\n#### 🔥 Top High-Value Deals to Focus On:\n";
            foreach ($topDeals as $td) {
                $text .= "1. **{$td->title}** — `$" . number_format($td->amount, 2) . "` (" . ($td->client->company ?? 'N/A') . ") | Stage: *" . ucfirst($td->stage) . "* ({$td->probability}% win probability)\n";
            }
            return $text;
        }

        // 3. Invoices & Overdue
        if (str_contains($q, 'invoice') || str_contains($q, 'pending') || str_contains($q, 'overdue') || str_contains($q, 'unpaid')) {
            $text = "### 📄 Invoice & Receivables Status\n\n";
            $text .= "You currently have **{$pendingInvoices->count()} pending invoices** totaling **$" . number_format($pendingRevenue, 2) . "**.\n\n";
            if ($pendingInvoices->count() > 0) {
                $text .= "| Invoice | Client | Company | Amount | Due Date | Status |\n";
                $text .= "| :--- | :--- | :--- | :--- | :--- | :--- |\n";
                foreach ($pendingInvoices as $inv) {
                    $isOverdue = ($inv->due_date && $inv->due_date->isPast()) ? '⚠️ Overdue' : 'Pending';
                    $text .= "| **{$inv->invoice_number}** | " . ($inv->client->name ?? 'N/A') . " | " . ($inv->client->company ?? 'N/A') . " | `$" . number_format($inv->amount, 2) . "` | " . ($inv->due_date ? $inv->due_date->format('M d, Y') : 'N/A') . " | {$isOverdue} |\n";
                }
            } else {
                $text .= "✅ All invoices are currently paid in full!";
            }
            return $text;
        }

        // 4. Clients
        if (str_contains($q, 'client') || str_contains($q, 'customer') || str_contains($q, 'account')) {
            $clients = Client::with(['invoices', 'deals'])->get()->sortByDesc(fn($c) => $c->lifetime_value)->take(5);
            $text = "### 👥 Active Clients & Account Intelligence\n\n";
            $text .= "You have **{$totalClients} registered clients** in your CRM.\n\n#### 🏆 Top Clients by Lifetime Revenue:\n\n";
            foreach ($clients as $c) {
                $text .= "- **{$c->name}** ({$c->company}) — **$" . number_format($c->lifetime_value, 2) . "** lifetime value | Email: `{$c->email}`\n";
            }
            return $text;
        }

        // 5. Leads & Scraper
        if (str_contains($q, 'lead') || str_contains($q, 'scrape') || str_contains($q, 'prospect')) {
            $hotLeads = $leads->where('lead_score', '>=', 80);
            $text = "### ⚡ AI Lead & Enrichment Intelligence\n\n";
            $text .= "You have **{$leads->count()} total leads** captured in your CRM, with **{$hotLeads->count()} marked as 🔥 Hot Leads** (Score ≥ 80):\n\n";
            foreach ($leads->take(4) as $l) {
                $text .= "- **{$l->name}** ({$l->company}) | Score: **{$l->lead_score}/100** | Industry: *{$l->industry}* | Status: `{$l->status}`\n";
            }
            $text .= "\n💡 *Tip: Click 'Convert to Client' on any lead to instantly spin up a customer profile and active deal in your pipeline.*";
            return $text;
        }

        // Default Comprehensive CRM Briefing
        return "### 🤖 CRM Pro Executive Intelligence Briefing\n\n" .
            "Here is the real-time operational summary of your business:\n\n" .
            "- **Total Collected Revenue:** `$" . number_format($totalRevenue, 2) . "`\n" .
            "- **Active Pipeline:** `$" . number_format($openDeals->sum('amount'), 2) . "` across **{$openDeals->count()} active deals**\n" .
            "- **Pending Invoices:** **{$pendingInvoices->count()} invoices** totaling `$" . number_format($pendingRevenue, 2) . "`\n" .
            "- **Client Base:** **{$totalClients} accounts**\n" .
            "- **Enriched Leads:** **{$leads->count()} prospects**\n\n" .
            "**What would you like me to do?**\n" .
            "1. `Analyze pipeline risks and closing forecast`\n" .
            "2. `Show me overdue invoices requiring immediate escalation`\n" .
            "3. `Draft a personalized cold outreach email for my top lead`\n" .
            "4. `Breakdown revenue by client and product category`";
    }

    protected function detectIndustry(string $title, string $desc, string $headings): string
    {
        $all = strtolower("{$title} {$desc} {$headings}");
        if (str_contains($all, 'photonic') || str_contains($all, 'supercomput') || str_contains($all, 'semiconductor') || str_contains($all, 'chip') || str_contains($all, 'hardware') || str_contains($all, 'optical') || str_contains($all, 'silicon')) return 'Photonic Supercomputing & AI Hardware';
        if (str_contains($all, 'pay') || str_contains($all, 'bank') || str_contains($all, 'fintech') || str_contains($all, 'card') || str_contains($all, 'finance') || str_contains($all, 'razorpay') || str_contains($all, 'stripe')) return 'FinTech & Payments';
        if (str_contains($all, 'health') || str_contains($all, 'med') || str_contains($all, 'patient') || str_contains($all, 'clinical') || str_contains($all, 'pharma')) return 'HealthTech & Medical';
        if (str_contains($all, 'ai') || str_contains($all, 'intelligence') || str_contains($all, 'neural') || str_contains($all, 'model') || str_contains($all, 'machine learning')) return 'Artificial Intelligence & DeepTech';
        if (str_contains($all, 'cloud') || str_contains($all, 'code') || str_contains($all, 'dev') || str_contains($all, 'framework') || str_contains($all, 'php') || str_contains($all, 'software') || str_contains($all, 'saas')) return 'Developer Tools & SaaS';
        if (str_contains($all, 'cyber') || str_contains($all, 'security') || str_contains($all, 'threat') || str_contains($all, 'auth')) return 'Cybersecurity';
        if (str_contains($all, 'energy') || str_contains($all, 'solar') || str_contains($all, 'green') || str_contains($all, 'power')) return 'Clean Energy & DeepTech';
        if (str_contains($all, 'shop') || str_contains($all, 'store') || str_contains($all, 'ecommerce') || str_contains($all, 'cart')) return 'E-Commerce & Retail';
        return 'Technology & Digital Services';
    }

    protected function detectTechStack(string $text): string
    {
        $text = strtolower($text);
        $tech = [];
        if (str_contains($text, 'photonic') || str_contains($text, 'silicon') || str_contains($text, 'optical')) $tech[] = 'Silicon Photonics & Optical Interconnects';
        if (str_contains($text, 'pytorch') || str_contains($text, 'tensorflow') || str_contains($text, 'cuda')) $tech[] = 'PyTorch / CUDA Acceleration';
        if (str_contains($text, 'laravel') || str_contains($text, 'php')) $tech[] = 'PHP / Laravel';
        if (str_contains($text, 'react') || str_contains($text, 'next')) $tech[] = 'React / Next.js';
        if (str_contains($text, 'vue') || str_contains($text, 'nuxt')) $tech[] = 'Vue.js';
        if (str_contains($text, 'python') || str_contains($text, 'django') || str_contains($text, 'fastapi')) $tech[] = 'Python AI Engine';
        if (str_contains($text, 'aws') || str_contains($text, 'cloud') || str_contains($text, 'azure') || str_contains($text, 'gcp')) $tech[] = 'Hyperscale Cloud Infrastructure';
        if (str_contains($text, 'stripe') || str_contains($text, 'payment') || str_contains($text, 'razorpay')) $tech[] = 'Stripe / Payments Gateway';
        if (str_contains($text, 'tailwind')) $tech[] = 'Tailwind CSS';
        if (str_contains($text, 'docker') || str_contains($text, 'kubernetes') || str_contains($text, 'k8s')) $tech[] = 'Docker / Kubernetes';

        if (empty($tech)) {
            return 'Modern Web Stack, REST API, Cloud Infrastructure';
        }
        return implode(', ', array_unique($tech));
    }
}
