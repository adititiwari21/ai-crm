@extends('layouts.app')

@section('title', 'AI Lead Intelligence & Scraper - CRM Pro')

@section('content')
<style>
    .lead-score-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }
    .score-hot {
        background-color: #fee2e2;
        color: #ef4444;
    }
    .dark .score-hot {
        background-color: rgba(239, 68, 68, 0.15);
    }
    .score-warm {
        background-color: #fef3c7;
        color: #d97706;
    }
    .dark .score-warm {
        background-color: rgba(245, 158, 11, 0.15);
    }
    .score-cold {
        background-color: #e0f2fe;
        color: #0284c7;
    }
    .dark .score-cold {
        background-color: rgba(2, 132, 199, 0.15);
    }

    .tech-pill {
        background: var(--bg-surface-hover);
        border: 1px solid var(--border-color);
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        color: var(--text-muted);
        display: inline-block;
        margin: 2px;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">AI Lead Intelligence & Website Scraper</h1>
        <p class="page-subtitle">Scrape any company website to extract tech stacks, generate AI lead scores, and draft tailored cold outreach emails.</p>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="button" class="btn btn-primary" onclick="openScrapeModal()">
            <i data-lucide="sparkles" style="width: 16px; height: 16px;"></i>
            <span>+ Scrape New Website</span>
        </button>
    </div>
</div>

<!-- 1. TOP QUICK SCRAPE BAR -->
<div class="card card-p" style="margin-bottom: 24px; background: linear-gradient(135deg, #f8fafc, #ffffff); border: 1px solid var(--border-color);">
    <form action="/scrape-company" method="POST" onsubmit="document.getElementById('quickScrapeBtn').disabled = true; document.getElementById('quickScrapeBtn').innerHTML = 'Scraping...';">
        @csrf
        <label class="form-label" style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="globe" style="width: 16px; height: 16px; color: var(--primary);"></i>
            <span>Instant Website & Company Scraper:</span>
        </label>
        <div style="display: flex; gap: 10px;">
            <input type="text" name="website" class="form-control" placeholder="Enter company domain or URL (e.g. https://lightmatter.co/ or stripe.com)" required style="flex: 1; font-size: 13.5px;">
            <button type="submit" class="btn btn-primary" id="quickScrapeBtn" style="white-space: nowrap; padding: 0 22px;">
                <i data-lucide="zap" style="width: 16px; height: 16px;"></i>
                <span>Scrape & Enrich</span>
            </button>
        </div>
    </form>
</div>

<div class="card" style="overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-family: var(--font-heading); font-size: 16px; font-weight: 800; color: var(--text-main);">Scraped Lead Records & AI Intel</h2>
        <span class="badge badge-blue">{{ $userDetails->count() }} Scraped Profiles</span>
    </div>

    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Company / Lead</th>
                    <th>AI Score</th>
                    <th>Industry & Tech Stack</th>
                    <th>Executive Summary</th>
                    <th>Outreach Pitch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userDetails as $lead)
                    @php
                        $scoreClass = $lead->lead_score >= 80 ? 'score-hot' : ($lead->lead_score >= 50 ? 'score-warm' : 'score-cold');
                        $scoreIcon = $lead->lead_score >= 80 ? '🔥' : ($lead->lead_score >= 50 ? '⚡' : '❄️');
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $lead->company ?: $lead->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $lead->email }}</div>
                            @if($lead->website)
                                <a href="{{ $lead->website }}" target="_blank" style="font-size: 11px; color: var(--primary); text-decoration: none;">
                                    <i data-lucide="external-link" style="width: 10px; height: 10px; display: inline-block;"></i>
                                    {{ parse_url($lead->website, PHP_URL_HOST) ?? $lead->website }}
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="lead-score-pill {{ $scoreClass }}">
                                <span>{{ $scoreIcon }}</span>
                                <span>{{ $lead->lead_score ?? 75 }}/100</span>
                            </div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">{{ $lead->status }}</div>
                        </td>
                        <td style="max-width: 200px;">
                            <div style="font-weight: 600; font-size: 12px; color: var(--text-main); margin-bottom: 4px;">{{ $lead->industry ?: 'Technology' }}</div>
                            <div>
                                @if($lead->tech_stack)
                                    @foreach(explode(',', $lead->tech_stack) as $t)
                                        <span class="tech-pill">{{ trim($t) }}</span>
                                    @endforeach
                                @else
                                    <span class="tech-pill">Web Platform</span>
                                @endif
                            </div>
                        </td>
                        <td style="max-width: 260px; font-size: 12.5px; color: var(--text-muted); line-height: 1.4;">
                            {{ $lead->ai_summary ?: ($lead->website_description ?: 'No AI summary generated.') }}
                        </td>
                        <td>
                            @if($lead->generated_pitch)
                                <button type="button" class="btn btn-secondary btn-sm" onclick="showPitchModal(`{{ addslashes($lead->company ?: $lead->name) }}`, `{{ addslashes($lead->generated_pitch) }}`)">
                                    <i data-lucide="mail" style="width: 13px; height: 13px;"></i>
                                    <span>View Pitch</span>
                                </button>
                            @else
                                <span style="font-size: 12px; color: var(--text-subtle);">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                @if($lead->status !== 'Converted')
                                    <form action="{{ route('leads.convert', $lead->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" title="1-Click Convert to Client & Deal">
                                            <i data-lucide="user-check" style="width: 14px; height: 14px;"></i>
                                            <span>Convert</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-won">Converted</span>
                                @endif

                                <form action="{{ route('user-details.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Delete this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">
                                        <i data-lucide="trash" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No scraped leads yet. Enter a website above or click "Scrape New Website" to start capturing intelligence!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Scrape Website -->
<div class="modal-backdrop" id="scrapeModal">
    <div class="modal-box card-p">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white;">
                    <i data-lucide="globe" style="width: 18px; height: 18px;"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">AI Website Scraper</h3>
            </div>
            <button type="button" onclick="closeScrapeModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <form action="/scrape-company" method="POST" id="modalScrapeForm">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Target Website URL / Domain *</label>
                    <input type="text" name="website" id="scrapeTargetUrl" class="form-control" placeholder="e.g. https://lightmatter.co/ or stripe.com" required>
                </div>
                <div id="scrapeLoadingState" style="display: none; padding: 14px; background: var(--bg-surface-hover); border-radius: var(--radius-sm); align-items: center; gap: 12px;">
                    <i data-lucide="loader-2" class="spin" style="color: var(--primary);"></i>
                    <div style="font-size: 13px; color: var(--text-main);">Scraping website & generating AI intelligence...</div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeScrapeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnScrapeRun" onclick="document.getElementById('scrapeLoadingState').style.display='flex';">
                        <i data-lucide="zap" style="width: 15px; height: 15px;"></i>
                        <span>Start Enrichment</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: View Cold Pitch -->
<div class="modal-backdrop" id="pitchModal">
    <div class="modal-box card-p" style="max-width: 650px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main);" id="pitchModalTitle">Cold Outreach Pitch</h3>
            <button type="button" onclick="closePitchModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <div style="background: var(--bg-surface-hover); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; font-size: 13.5px; line-height: 1.6; white-space: pre-wrap; color: var(--text-main);" id="pitchModalContent"></div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px;">
            <button type="button" class="btn btn-primary" onclick="copyPitchToClipboard()">
                <i data-lucide="copy" style="width: 15px; height: 15px;"></i>
                <span>Copy Pitch</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openScrapeModal() {
        document.getElementById('scrapeModal').style.display = 'flex';
    }
    function closeScrapeModal() {
        document.getElementById('scrapeModal').style.display = 'none';
    }

    async function executeScrape(e) {
        e.preventDefault();
        const url = document.getElementById('scrapeTargetUrl').value;
        const loader = document.getElementById('scrapeLoadingState');
        const btn = document.getElementById('btnScrapeRun');

        loader.style.display = 'flex';
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('scrape.company') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ website: url })
            });
            const data = await res.json();
            if (data.success) {
                alert('✅ Enriched successfully!\n\nScore: ' + data.lead_score + '/100\nIndustry: ' + data.industry);
                window.location.reload();
            } else {
                alert('Scraping status: ' + (data.error || 'Saved'));
                window.location.reload();
            }
        } catch (err) {
            window.location.reload();
        }
    }

    function showPitchModal(company, pitch) {
        document.getElementById('pitchModalTitle').textContent = 'AI Outreach Pitch: ' + company;
        document.getElementById('pitchModalContent').textContent = pitch;
        document.getElementById('pitchModal').style.display = 'flex';
    }

    function closePitchModal() {
        document.getElementById('pitchModal').style.display = 'none';
    }

    function copyPitchToClipboard() {
        const text = document.getElementById('pitchModalContent').textContent;
        navigator.clipboard.writeText(text);
        alert('📋 Pitch copied to clipboard!');
    }
</script>
@endpush
@endsection