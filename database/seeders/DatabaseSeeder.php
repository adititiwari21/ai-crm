<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\UserDetail;
use App\Models\Deal;
use App\Models\ClientActivity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@aicrm.io'],
            [
                'name' => 'Alex Rivera',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Clients
        $clientsData = [
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.j@acmecorp.com',
                'phone' => '+1 (555) 234-5678',
                'company' => 'Acme Technologies Inc.',
            ],
            [
                'name' => 'David Chen',
                'email' => 'dchen@nexuslogistics.io',
                'phone' => '+1 (555) 876-5432',
                'company' => 'Nexus Global Logistics',
            ],
            [
                'name' => 'Elena Rostova',
                'email' => 'elena@quantumscale.ai',
                'phone' => '+44 20 7946 0912',
                'company' => 'QuantumScale AI',
            ],
            [
                'name' => 'Marcus Aurelius',
                'email' => 'marcus@solarisenergy.de',
                'phone' => '+49 30 123456',
                'company' => 'Solaris Green Energy',
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya@fintechflow.in',
                'phone' => '+91 98765 43210',
                'company' => 'Fintech Flow Solutions',
            ],
            [
                'name' => 'Liam O\'Connor',
                'email' => 'liam@vortexcyber.com',
                'phone' => '+1 (555) 345-6789',
                'company' => 'Vortex Cybersecurity',
            ],
        ];

        $clients = [];
        foreach ($clientsData as $c) {
            $clients[] = Client::updateOrCreate(['email' => $c['email']], $c);
        }

        // 3. Products
        $productsData = [
            [
                'name' => 'Enterprise AI Copilot License (Annual)',
                'category' => 'Software SaaS',
                'price' => 4999.00,
                'stock' => 50,
                'description' => 'Unlimited multi-modal AI querying, custom CRM context embeddings, 99.9% uptime SLA.',
            ],
            [
                'name' => 'Dedicated Vector Search Endpoint',
                'category' => 'Infrastructure',
                'price' => 1250.00,
                'stock' => 12,
                'description' => 'High-throughput dedicated Qdrant/Pinecone instance with real-time indexing.',
            ],
            [
                'name' => 'Custom AI Workflow Setup & Integration',
                'category' => 'Professional Services',
                'price' => 3500.00,
                'stock' => 8,
                'description' => 'End-to-end bespoke pipeline engineering, ETL automation, and team onboarding.',
            ],
            [
                'name' => 'Lead Scraper & Intelligence API (10k credits)',
                'category' => 'API Credits',
                'price' => 450.00,
                'stock' => 100,
                'description' => 'Automated website scraping, tech stack detection, and AI cold email generation.',
            ],
            [
                'name' => '24/7 Priority Support & Dedicated CSM',
                'category' => 'Support Tier',
                'price' => 850.00,
                'stock' => 20,
                'description' => 'Sub-1 hour response times, quarterly business reviews, and dedicated Slack channel.',
            ],
        ];

        foreach ($productsData as $p) {
            Product::updateOrCreate(['name' => $p['name']], $p);
        }

        // 4. Sales Records
        Sale::truncate();
        Sale::create([
            'client_id' => $clients[0]->id,
            'amount' => 14500.00,
            'sale_date' => Carbon::now()->subDays(12)->toDateString(),
            'status' => 'Paid',
        ]);
        Sale::create([
            'client_id' => $clients[1]->id,
            'amount' => 8200.00,
            'sale_date' => Carbon::now()->subDays(5)->toDateString(),
            'status' => 'Paid',
        ]);
        Sale::create([
            'client_id' => $clients[2]->id,
            'amount' => 25000.00,
            'sale_date' => Carbon::now()->subDays(2)->toDateString(),
            'status' => 'Paid',
        ]);
        Sale::create([
            'client_id' => $clients[3]->id,
            'amount' => 6400.00,
            'sale_date' => Carbon::now()->subDays(18)->toDateString(),
            'status' => 'Pending',
        ]);
        Sale::create([
            'client_id' => $clients[4]->id,
            'amount' => 11800.00,
            'sale_date' => Carbon::now()->subDays(25)->toDateString(),
            'status' => 'Paid',
        ]);

        // 5. Invoices with itemized breakdowns
        Invoice::truncate();
        Invoice::create([
            'client_id' => $clients[0]->id,
            'invoice_number' => 'INV-2026-001',
            'amount' => 14500.00,
            'invoice_date' => Carbon::now()->subDays(15)->toDateString(),
            'due_date' => Carbon::now()->addDays(15)->toDateString(),
            'status' => 'Paid',
            'items' => [
                ['description' => 'Enterprise AI Copilot Annual Subscription', 'quantity' => 2, 'unit_price' => 4999.00, 'total' => 9998.00],
                ['description' => 'Custom AI Workflow Setup & Integration', 'quantity' => 1, 'unit_price' => 3500.00, 'total' => 3500.00],
                ['description' => 'Priority Slack & Dedicated CSM (Monthly)', 'quantity' => 1, 'unit_price' => 1002.00, 'total' => 1002.00],
            ],
            'tax_rate' => 0.0,
            'discount' => 0.0,
            'notes' => 'Thank you for your business. Payment received via Wire Transfer.',
        ]);

        Invoice::create([
            'client_id' => $clients[2]->id,
            'invoice_number' => 'INV-2026-002',
            'amount' => 25000.00,
            'invoice_date' => Carbon::now()->subDays(4)->toDateString(),
            'due_date' => Carbon::now()->addDays(26)->toDateString(),
            'status' => 'Paid',
            'items' => [
                ['description' => 'QuantumScale Dedicated Cluster Deployment', 'quantity' => 1, 'unit_price' => 20000.00, 'total' => 20000.00],
                ['description' => 'Fine-Tuning & Custom Embedding Setup', 'quantity' => 1, 'unit_price' => 5000.00, 'total' => 5000.00],
            ],
            'tax_rate' => 0.0,
            'discount' => 0.0,
            'notes' => 'Net 30 terms. Paid in full.',
        ]);

        Invoice::create([
            'client_id' => $clients[3]->id,
            'invoice_number' => 'INV-2026-003',
            'amount' => 6400.00,
            'invoice_date' => Carbon::now()->subDays(10)->toDateString(),
            'due_date' => Carbon::now()->addDays(5)->toDateString(),
            'status' => 'Pending',
            'items' => [
                ['description' => 'Solaris Energy Grid AI Telemetry Sync', 'quantity' => 1, 'unit_price' => 4999.00, 'total' => 4999.00],
                ['description' => 'Dedicated Vector Search Endpoint', 'quantity' => 1, 'unit_price' => 1401.00, 'total' => 1401.00],
            ],
            'tax_rate' => 0.0,
            'discount' => 0.0,
            'notes' => 'Invoice due in 5 business days.',
        ]);

        Invoice::create([
            'client_id' => $clients[5]->id,
            'invoice_number' => 'INV-2026-004',
            'amount' => 9200.00,
            'invoice_date' => Carbon::now()->subDays(30)->toDateString(),
            'due_date' => Carbon::now()->subDays(2)->toDateString(),
            'status' => 'Pending',
            'items' => [
                ['description' => 'Vortex Security Threat AI Anomaly Engine', 'quantity' => 1, 'unit_price' => 9200.00, 'total' => 9200.00],
            ],
            'tax_rate' => 0.0,
            'discount' => 0.0,
            'notes' => 'URGENT: This invoice is currently overdue.',
        ]);

        // 6. Deals for Pipeline Kanban
        Deal::truncate();
        Deal::create([
            'client_id' => $clients[0]->id,
            'title' => 'Acme Global Expansion Tier 2',
            'amount' => 38000.00,
            'stage' => 'negotiation',
            'probability' => 85,
            'expected_close_date' => Carbon::now()->addDays(14)->toDateString(),
            'notes' => 'Legal reviewing MSA terms. Expected signoff next Tuesday.',
        ]);

        Deal::create([
            'client_id' => $clients[1]->id,
            'title' => 'Nexus Fleet AI Routing Engine',
            'amount' => 22500.00,
            'stage' => 'proposal',
            'probability' => 60,
            'expected_close_date' => Carbon::now()->addDays(21)->toDateString(),
            'notes' => 'Proposal presented to CTO. Awaiting procurement committee feedback.',
        ]);

        Deal::create([
            'client_id' => $clients[2]->id,
            'title' => 'QuantumScale Multi-Region LLM Mesh',
            'amount' => 65000.00,
            'stage' => 'won',
            'probability' => 100,
            'expected_close_date' => Carbon::now()->subDays(3)->toDateString(),
            'notes' => 'Closed won! Contract executed for 24-month commitment.',
        ]);

        Deal::create([
            'client_id' => $clients[3]->id,
            'title' => 'Solaris Smart Grid Predictive Maintenance',
            'amount' => 17500.00,
            'stage' => 'qualified',
            'probability' => 40,
            'expected_close_date' => Carbon::now()->addDays(35)->toDateString(),
            'notes' => 'Technical discovery call completed. High customer buying intent.',
        ]);

        Deal::create([
            'client_id' => $clients[4]->id,
            'title' => 'Fintech Flow Real-time Fraud Detection',
            'amount' => 45000.00,
            'stage' => 'proposal',
            'probability' => 70,
            'expected_close_date' => Carbon::now()->addDays(18)->toDateString(),
            'notes' => 'Shared security whitepaper and latency benchmarks.',
        ]);

        Deal::create([
            'client_id' => $clients[5]->id,
            'title' => 'Vortex Zero-Trust Identity Guard',
            'amount' => 28000.00,
            'stage' => 'lead',
            'probability' => 25,
            'expected_close_date' => Carbon::now()->addDays(45)->toDateString(),
            'notes' => 'Inbound request from webinar demo. Scheduled discovery call for Friday.',
        ]);

        // 7. Client 360 Activities Timeline
        ClientActivity::truncate();
        ClientActivity::create([
            'client_id' => $clients[0]->id,
            'type' => 'call',
            'description' => 'Executive quarterly review call with VP of Engineering. Discussed expanding to 500 seat licenses.',
            'performed_at' => Carbon::now()->subDays(2),
        ]);
        ClientActivity::create([
            'client_id' => $clients[0]->id,
            'type' => 'meeting',
            'description' => 'Solution Architecture workshop for LLM integration pipeline.',
            'performed_at' => Carbon::now()->subDays(6),
        ]);
        ClientActivity::create([
            'client_id' => $clients[0]->id,
            'type' => 'note',
            'description' => 'Key stakeholder preference: prefers async communication on Slack over email.',
            'performed_at' => Carbon::now()->subDays(10),
        ]);
        ClientActivity::create([
            'client_id' => $clients[1]->id,
            'type' => 'email',
            'description' => 'Sent revised proposal with multi-warehouse telemetry option included.',
            'performed_at' => Carbon::now()->subHours(18),
        ]);
        ClientActivity::create([
            'client_id' => $clients[2]->id,
            'type' => 'meeting',
            'description' => 'Contract signing champagne kickoff with Elena and leadership team!',
            'performed_at' => Carbon::now()->subDays(3),
        ]);

        // 8. UserDetails / AI Enriched Leads
        UserDetail::truncate();
        UserDetail::create([
            'name' => 'Samantha Vance',
            'email' => 'svance@hypercloud.dev',
            'phone' => '+1 (555) 901-2345',
            'company' => 'HyperCloud Systems',
            'website' => 'https://laravel.com',
            'requirements' => 'Looking for an AI-native CRM with automated customer telemetry and pipeline forecasting.',
            'website_title' => 'Laravel - The PHP Framework For Web Artisans',
            'website_description' => 'Laravel is a PHP web application framework with expressive, elegant syntax.',
            'website_headings' => "Build at the speed of thought\nScalable infrastructure\nEcosystem of modern tools",
            'ai_summary' => 'HyperCloud is a high-growth developer infrastructure provider building cloud tooling with massive developer adoption.',
            'industry' => 'Cloud & Developer Tooling',
            'target_audience' => 'DevOps Engineers, Software Architects, and CTOs',
            'tech_stack' => 'PHP, Laravel, Vue.js, Tailwind CSS, PostgreSQL, AWS',
            'lead_score' => 94,
            'generated_pitch' => "Hi Samantha,\n\nI noticed HyperCloud's incredible velocity in expanding developer tooling. As your enterprise accounts scale, manual pipeline tracking can introduce friction.\n\nOur AI CRM automatically enriches leads and gives your sales reps intelligent deal win-probability in real-time.\n\nWould you have 10 minutes this Thursday for a quick demo?",
            'status' => 'Qualified',
        ]);

        UserDetail::create([
            'name' => 'Rajesh Kothari',
            'email' => 'rajesh@aerospike-logistics.com',
            'phone' => '+91 99887 76655',
            'company' => 'AeroSpike Supply Chain',
            'website' => 'https://stripe.com',
            'requirements' => 'Need unified multi-currency invoice tracking and client communication logs.',
            'website_title' => 'Financial Infrastructure for the Internet',
            'website_description' => 'Millions of businesses of all sizes use Stripe online and in person to accept payments.',
            'website_headings' => "Global Payments\nFinancial Automation\nAI Risk Defense",
            'ai_summary' => 'Leading financial technology suite enabling payment processing, billing, and global financial operations.',
            'industry' => 'Fintech & Payment Infrastructure',
            'target_audience' => 'Global enterprises, SaaS platforms, and eCommerce businesses',
            'tech_stack' => 'Ruby, Go, React, Kafka, Kubernetes, Stripe API',
            'lead_score' => 88,
            'generated_pitch' => "Hi Rajesh,\n\nManaging multi-currency invoice lifecycles across distributed logistics nodes requires razor-sharp automation.\n\nOur AI CRM syncs invoice states and generates print-ready professional PDF billing with automated overdue escalations.\n\nLet's schedule a 15-minute walkthrough next week.",
            'status' => 'New',
        ]);

        UserDetail::create([
            'name' => 'Julian Brandt',
            'email' => 'jbrandt@nordicpulse.se',
            'phone' => '+46 8 123 4567',
            'company' => 'NordicPulse HealthTech',
            'website' => 'https://nordicpulse.example.com',
            'requirements' => 'Medical device monitoring CRM with strict HIPAA/GDPR audit trails.',
            'website_title' => 'NordicPulse - Connected Health Devices',
            'website_description' => 'Real-time patient telemetry for hospitals and clinics.',
            'website_headings' => "Clinical Precision\nSecure Patient Monitoring\nCompliance First",
            'ai_summary' => 'Healthcare technology manufacturer providing connected wearable monitors and medical clinical software.',
            'industry' => 'HealthTech & MedTech',
            'target_audience' => 'Hospitals, Private Clinics, and Healthcare Providers',
            'tech_stack' => 'Python, FastAPI, React, Docker, Azure Health Cloud',
            'lead_score' => 76,
            'generated_pitch' => "Hi Julian,\n\nGiven NordicPulse's expansion into hospital networks, having structured 360° interaction timelines and deal compliance logs is critical.\n\nOur AI CRM maintains complete audit trails across every meeting and proposal stage.\n\nWould you be open to exploring how we streamline healthcare sales pipelines?",
            'status' => 'Contacted',
        ]);
    }
}
