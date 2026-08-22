<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\UserDetail;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserDetailController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function index()
    {
        $userDetails = UserDetail::latest()->get();
        return view('user-details-list', compact('userDetails'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',
        ]);

        $lead = UserDetail::create($validated);

        // If website is provided, trigger automatic AI enrichment
        if (!empty($validated['website'])) {
            $this->enrichLeadWithAi($lead, $validated['website']);
        }

        return redirect()->route('user-details.list')
            ->with('success', 'Lead saved and automatically enriched with AI intelligence!');
    }

    public function scrapeWebsite(Request $request)
    {
        $websiteInput = trim($request->input('website', ''));
        if (!empty($websiteInput) && !preg_match('#^https?://#i', $websiteInput)) {
            $websiteInput = 'https://' . $websiteInput;
        }

        $request->merge(['website' => $websiteInput]);

        $request->validate([
            'website' => 'required|url|max:255',
        ]);

        $website = $websiteInput;

        try {
            // 1. Fetch website HTML via cURL for highest compatibility
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $website);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($html === false || empty($html) || $httpCode >= 400) {
                $domain = parse_url($website, PHP_URL_HOST) ?? $website;
                $html = "<html><head><title>{$domain}</title></head><body><h1>{$domain} Digital Business</h1></body></html>";
            }

            // Extract Title
            $title = 'Company Profile';
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            }

            // Extract Description
            $description = 'Modern digital solutions provider.';
            if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/is', $html, $m)) {
                $description = trim(html_entity_decode($m[1]));
            }

            // Extract Headings
            $headings = [];
            if (preg_match_all('/<h[1-3][^>]*>(.*?)<\/h[1-3]>/is', $html, $m)) {
                foreach ($m[1] as $h) {
                    $cleaned = trim(html_entity_decode(strip_tags($h)));
                    if ($cleaned) $headings[] = $cleaned;
                }
            }
            $headingsText = implode("\n", array_slice(array_unique($headings), 0, 10));

            // 2. Invoke Gemini AI Service for deep enrichment
            $aiData = $this->gemini->analyzeCompanyWebsite($website, $title, $description, $headingsText);

            $domain = parse_url($website, PHP_URL_HOST) ?? $website;
            $cleanDomain = str_replace('www.', '', $domain);
            $companyName = ucfirst(explode('.', $cleanDomain)[0]);

            // 3. Create or update Lead record in CRM database automatically
            $lead = UserDetail::updateOrCreate(
                ['website' => $website],
                [
                    'name' => $companyName . ' Representative',
                    'email' => 'contact@' . $cleanDomain,
                    'company' => $companyName,
                    'website_title' => $title,
                    'website_description' => $description,
                    'website_headings' => $headingsText,
                    'ai_summary' => $aiData['ai_summary'],
                    'industry' => $aiData['industry'],
                    'target_audience' => $aiData['target_audience'],
                    'tech_stack' => $aiData['tech_stack'],
                    'lead_score' => $aiData['lead_score'],
                    'generated_pitch' => $aiData['generated_pitch'],
                    'status' => $aiData['lead_score'] >= 80 ? 'Qualified' : 'New',
                ]
            );

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'website' => $website,
                    'company' => $companyName,
                    'lead_score' => $aiData['lead_score'],
                    'industry' => $aiData['industry'],
                    'ai_summary' => $aiData['ai_summary'],
                    'lead_id' => $lead->id,
                ]);
            }

            return back()
                ->with('success', "Website '{$companyName}' scraped & enriched! Lead Score: {$aiData['lead_score']}/100, Industry: {$aiData['industry']}");
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'website' => $website,
                ]);
            }

            return back()
                ->with('error', 'Error scraping website: ' . $e->getMessage());
        }
    }

    /**
     * 1-Click Convert Lead to Client & Pipeline Deal
     */
    public function convertToClient($id)
    {
        $lead = UserDetail::findOrFail($id);

        // 1. Create or find Client
        $client = Client::firstOrCreate(
            ['email' => $lead->email],
            [
                'name' => $lead->name,
                'phone' => $lead->phone,
                'company' => $lead->company ?: 'Prospective Enterprise',
            ]
        );

        // 2. Create an initial active Deal in the pipeline
        Deal::create([
            'client_id' => $client->id,
            'title' => ($lead->company ?: $lead->name) . ' Expansion Deal',
            'amount' => 25000.00,
            'stage' => 'qualified',
            'probability' => $lead->lead_score ?: 60,
            'expected_close_date' => Carbon::now()->addDays(30),
            'notes' => "Converted automatically from AI Lead Scraper.\nAI Summary: " . $lead->ai_summary,
        ]);

        $lead->update(['status' => 'Converted']);

        return redirect()->route('clients.show', $client->id)
            ->with('success', "Lead '{$lead->name}' converted to Client with an active $25,000 Deal in Pipeline!");
    }

    public function destroy($id)
    {
        $lead = UserDetail::findOrFail($id);
        $lead->delete();

        return redirect()->route('user-details.list')
            ->with('success', 'Lead record deleted successfully.');
    }

    protected function enrichLeadWithAi(UserDetail $lead, string $website)
    {
        $domain = parse_url($website, PHP_URL_HOST) ?? $website;
        $aiData = $this->gemini->analyzeCompanyWebsite($website, $lead->company ?: 'Company', $lead->requirements ?: '', '');
        $lead->update([
            'ai_summary' => $aiData['ai_summary'],
            'industry' => $aiData['industry'],
            'target_audience' => $aiData['target_audience'],
            'tech_stack' => $aiData['tech_stack'],
            'lead_score' => $aiData['lead_score'],
            'generated_pitch' => $aiData['generated_pitch'],
        ]);
    }
}