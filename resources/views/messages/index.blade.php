@extends('layouts.app')

@section('title', 'Messages & Team Communications - CRM Pro')

@section('content')
<style>
    .msg-layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        height: calc(100vh - 160px);
    }
    @media (max-width: 768px) {
        .msg-layout-grid {
            grid-template-columns: 1fr;
            height: auto;
        }
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Team Messages & Communications</h1>
        <p class="page-subtitle">Real-time internal chat, customer communication notes, and team handoffs.</p>
    </div>
</div>

<div class="card msg-layout-grid" style="overflow: hidden;">
    <!-- Left: Channels / Contacts list -->
    <div style="border-right: 1px solid var(--border-color); display: flex; flex-direction: column; background: var(--bg-surface);">
        <div style="padding: 16px; border-bottom: 1px solid var(--border-color); font-weight: 700; font-size: 14px;">
            Channels & Threads
        </div>
        <div style="padding: 12px; display: flex; flex-direction: column; gap: 6px; overflow-y: auto;">
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--radius-sm); background: var(--primary-light); color: var(--primary); font-weight: 600; font-size: 13px; cursor: pointer;">
                <i data-lucide="hash" style="width: 16px; height: 16px;"></i>
                <span>General Sales Team</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--radius-sm); color: var(--text-muted); font-size: 13px; cursor: pointer;">
                <i data-lucide="hash" style="width: 16px; height: 16px;"></i>
                <span>Enterprise Deal Desk</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--radius-sm); color: var(--text-muted); font-size: 13px; cursor: pointer;">
                <i data-lucide="hash" style="width: 16px; height: 16px;"></i>
                <span>Client Support Handoffs</span>
            </div>
        </div>
    </div>

    <!-- Right: Active Chat Zone -->
    <div style="display: flex; flex-direction: column; height: 100%;">
        <div style="padding: 14px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 10px; background: var(--bg-surface);">
            <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--success);"></div>
            <span style="font-weight: 700; font-size: 14px;"># General Sales Team</span>
        </div>

        <div style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 14px;" id="msgZone">
            @forelse($messages as $m)
                <div style="display: flex; gap: 12px; max-width: 80%;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                        {{ substr($m->sender_name, 0, 2) }}
                    </div>
                    <div>
                        <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 2px;">
                            <span style="font-size: 12.5px; font-weight: 700; color: var(--text-main);">{{ $m->sender_name }}</span>
                            <span style="font-size: 11px; color: var(--text-muted);">{{ $m->created_at ? $m->created_at->format('h:i A') : 'Now' }}</span>
                        </div>
                        <div style="background: var(--bg-surface-hover); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 10px 14px; font-size: 13px; line-height: 1.5; color: var(--text-main);">
                            {{ $m->message }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 40px; margin: auto;">
                    Welcome to #General Sales Team. Send a message to start collaborating!
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div style="padding: 16px 20px; border-top: 1px solid var(--border-color); background: var(--bg-surface);">
            <form action="{{ route('messages.store') }}" method="POST" style="display: flex; gap: 10px;">
                @csrf
                <input type="text" name="message" class="form-control" placeholder="Type a message to the team..." required autocomplete="off">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const zone = document.getElementById('msgZone');
    if (zone) zone.scrollTop = zone.scrollHeight;
</script>
@endpush
@endsection
