@extends('layouts.app')

@section('title', 'AI Copilot Assistant - CRM Pro')

@section('content')
<style>
    .ai-layout-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
        height: calc(100vh - 150px);
    }

    @media (max-width: 1024px) {
        .ai-layout-grid {
            grid-template-columns: 1fr;
            height: auto;
        }
    }

    .chat-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ai-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #10b981;
        background: #ecfdf5;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .dark .ai-badge-status {
        background: rgba(16, 185, 129, 0.15);
    }

    .chat-messages-zone {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .msg-row {
        display: flex;
        gap: 12px;
        max-width: 85%;
    }

    .msg-user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .msg-assistant {
        align-self: flex-start;
    }

    .msg-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .avatar-ai {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 4px 10px rgba(99,102,241,0.3);
    }

    .avatar-user {
        background: #3b82f6;
        color: white;
    }

    .msg-bubble {
        padding: 14px 18px;
        border-radius: var(--radius-lg);
        font-size: 13.5px;
        line-height: 1.6;
    }

    .bubble-user {
        background: var(--primary);
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }

    .bubble-assistant {
        background: var(--bg-surface-hover);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-bottom-left-radius: 4px;
    }

    .bubble-assistant h3, .bubble-assistant h4 {
        color: var(--primary);
    }

    .bubble-assistant ul, .bubble-assistant ol {
        margin-left: 18px;
        margin-top: 6px;
    }

    .chat-input-area {
        padding: 16px 20px;
        border-top: 1px solid var(--border-color);
        background: var(--bg-surface);
    }

    .quick-prompts-bar {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 12px;
        scrollbar-width: none;
    }

    .prompt-chip {
        background: var(--bg-surface-hover);
        border: 1px solid var(--border-color);
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 12px;
        color: var(--text-muted);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s;
    }
    .prompt-chip:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .input-box-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        padding: 6px 8px 6px 18px;
    }

    .chat-input-field {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        color: var(--text-main);
        font-size: 14px;
    }

    .btn-send {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.15s;
    }
    .btn-send:hover {
        transform: scale(1.05);
    }

    /* RIGHT SIDEBAR STATS */
    .ai-stats-panel {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .mini-stat-card {
        padding: 16px;
        border-radius: var(--radius-md);
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
    }

    .mini-stat-title {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .mini-stat-val {
        font-family: var(--font-heading);
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
    }
</style>

<div class="ai-layout-grid">
    <!-- Main Chat Window -->
    <div class="card chat-card">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white;">
                    <i data-lucide="sparkles" style="width: 18px; height: 18px;"></i>
                </div>
                <div>
                    <h2 style="font-size: 15px; font-weight: 700; color: var(--text-main);">CRM Pro AI Copilot</h2>
                    <span style="font-size: 11.5px; color: var(--text-muted);">Connected to Live Database Context</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="ai-badge-status">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                    Online
                </div>
                <form action="{{ route('ai.clear') }}" method="POST" onsubmit="return confirm('Clear chat history?');">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" title="Clear History">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Chat Messages Area -->
        <div class="chat-messages-zone" id="chatZone">
            @foreach($messages as $msg)
                <div class="msg-row {{ $msg->role === 'user' ? 'msg-user' : 'msg-assistant' }}">
                    <div class="msg-avatar {{ $msg->role === 'user' ? 'avatar-user' : 'avatar-ai' }}">
                        @if($msg->role === 'user')
                            <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                        @else
                            <i data-lucide="sparkles" style="width: 16px; height: 16px;"></i>
                        @endif
                    </div>
                    <div class="msg-bubble {{ $msg->role === 'user' ? 'bubble-user' : 'bubble-assistant' }}">
                        {!! nl2br(e($msg->content)) !!}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <div class="quick-prompts-bar">
                <button type="button" class="prompt-chip" onclick="askQuick('Give me a full Financial & Revenue Summary')">💰 Revenue Summary</button>
                <button type="button" class="prompt-chip" onclick="askQuick('Show me all active deals in the pipeline')">💼 Active Deals</button>
                <button type="button" class="prompt-chip" onclick="askQuick('Which invoices are currently pending or overdue?')">📄 Pending Invoices</button>
                <button type="button" class="prompt-chip" onclick="askQuick('Who are our top clients by lifetime revenue?')">👥 Top Clients</button>
                <button type="button" class="prompt-chip" onclick="askQuick('Show me our hot leads with scores above 80')">🔥 Hot Leads</button>
            </div>

            <form id="aiChatForm" onsubmit="handleChatSubmit(event)">
                <div class="input-box-wrapper">
                    <input type="text" id="aiInput" class="chat-input-field" placeholder="Ask anything about clients, revenue, pipeline, or invoices..." autocomplete="off" required>
                    <button type="submit" class="btn-send" id="btnSend">
                        <i data-lucide="arrow-up" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Live CRM Pulse Panel -->
    <div class="ai-stats-panel">
        <div style="font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 2px;">
            <i data-lucide="activity" style="width: 15px; height: 15px; display: inline-block; vertical-align: -2px; color: var(--primary);"></i>
            Live Database Pulse
        </div>

        <div class="mini-stat-card">
            <div class="mini-stat-title">Collected Revenue</div>
            <div class="mini-stat-val" style="color: var(--success);">${{ number_format($totalRevenue, 0) }}</div>
        </div>

        <div class="mini-stat-card">
            <div class="mini-stat-title">Active Deals Pipeline</div>
            <div class="mini-stat-val" style="color: var(--primary);">${{ number_format($openDealsSum, 0) }}</div>
        </div>

        <div class="mini-stat-card">
            <div class="mini-stat-title">Pending Invoices</div>
            <div class="mini-stat-val" style="color: #f59e0b;">{{ $pendingInvoicesCount }}</div>
        </div>

        <div class="mini-stat-card">
            <div class="mini-stat-title">Accounts & Leads</div>
            <div class="mini-stat-val">{{ $totalClients }} Clients / {{ $totalLeads }} Leads</div>
        </div>

        <div class="card card-p" style="background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(139,92,246,0.04));">
            <div style="font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 4px;">⚡ AI Tip</div>
            <div style="font-size: 12px; color: var(--text-muted); line-height: 1.5;">
                Ask the copilot to draft cold emails, calculate quarterly growth, or identify at-risk accounts.
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatZone = document.getElementById('chatZone');
    chatZone.scrollTop = chatZone.scrollHeight;

    function askQuick(q) {
        document.getElementById('aiInput').value = q;
        document.getElementById('aiChatForm').dispatchEvent(new Event('submit'));
    }

    async function handleChatSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('aiInput');
        const prompt = input.value.trim();
        if (!prompt) return;

        input.value = '';

        // Append User Bubble
        appendBubble('user', prompt);

        // Append Loading Bubble
        const loadingId = 'loading-' + Date.now();
        appendBubble('assistant', 'Thinking and querying live CRM database...', loadingId);

        try {
            const res = await fetch("{{ route('ai.ask') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ question: prompt, conversation_id: {{ $conversation->id }} })
            });

            const data = await res.json();
            const loadingBubble = document.getElementById(loadingId);
            if (loadingBubble) {
                loadingBubble.innerHTML = data.html || data.message;
            }
        } catch (err) {
            const loadingBubble = document.getElementById(loadingId);
            if (loadingBubble) {
                loadingBubble.innerHTML = 'Error communicating with AI. Please check your network or try again.';
            }
        }

        chatZone.scrollTop = chatZone.scrollHeight;
    }

    function appendBubble(role, text, id = null) {
        const row = document.createElement('div');
        row.className = `msg-row ${role === 'user' ? 'msg-user' : 'msg-assistant'}`;
        
        const avatar = document.createElement('div');
        avatar.className = `msg-avatar ${role === 'user' ? 'avatar-user' : 'avatar-ai'}`;
        avatar.innerHTML = role === 'user' ? '<i data-lucide="user" style="width: 16px; height: 16px;"></i>' : '<i data-lucide="sparkles" style="width: 16px; height: 16px;"></i>';

        const bubble = document.createElement('div');
        bubble.className = `msg-bubble ${role === 'user' ? 'bubble-user' : 'bubble-assistant'}`;
        if (id) bubble.id = id;
        bubble.textContent = text;

        row.appendChild(avatar);
        row.appendChild(bubble);
        chatZone.appendChild(row);
        lucide.createIcons();
        chatZone.scrollTop = chatZone.scrollHeight;
    }
</script>
@endpush
@endsection