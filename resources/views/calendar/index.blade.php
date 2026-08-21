@extends('layouts.app')

@section('title', 'Calendar & Schedule - CRM Pro')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Calendar & Schedules</h1>
        <p class="page-subtitle">Manage client meetings, discovery calls, demos, and closing deadlines.</p>
    </div>

    <div>
        <button type="button" class="btn btn-primary" onclick="openAddEventModal()">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Schedule Event</span>
        </button>
    </div>
</div>

<div class="card card-p">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main);">Scheduled Agenda ({{ $events->count() }})</h3>
        <span style="font-size: 12px; color: var(--text-muted);">{{ now()->format('F Y') }}</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px;">
        @forelse($events as $ev)
            @php
                $icon = match($ev->event_type) {
                    'call' => 'phone',
                    'meeting' => 'users',
                    'deadline' => 'alert-circle',
                    default => 'calendar'
                };
                $badgeBg = match($ev->event_type) {
                    'call' => '#e0f2fe',
                    'meeting' => '#dcfce7',
                    'deadline' => '#fee2e2',
                    default => '#f3e8ff'
                };
                $badgeColor = match($ev->event_type) {
                    'call' => '#0284c7',
                    'meeting' => '#15803d',
                    'deadline' => '#b91c1c',
                    default => '#7e22ce'
                };
            @endphp
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-radius: var(--radius-md); background: var(--bg-surface-hover); border: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: {{ $badgeBg }}; color: {{ $badgeColor }}; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="{{ $icon }}" style="width: 18px; height: 18px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $ev->title }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">
                            {{ $ev->client->company ?? ($ev->client->name ?? 'General Agenda') }}
                            @if($ev->description) • {{ $ev->description }} @endif
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="text-align: right; font-size: 12.5px;">
                        <div style="font-weight: 700; color: var(--text-main);">{{ $ev->start_time->format('M d, Y') }}</div>
                        <div style="color: var(--text-muted);">{{ $ev->start_time->format('h:i A') }}</div>
                    </div>
                    <form action="{{ route('calendar.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Remove event?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">
                            <i data-lucide="trash" style="width: 14px; height: 14px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                No calendar events scheduled. Click "Schedule Event" to add meetings, discovery calls, or deadlines.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal: Add Event -->
<div class="modal-backdrop" id="addEventModal">
    <div class="modal-box card-p">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Schedule Calendar Event</h3>
            <button type="button" onclick="closeAddEventModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <form action="{{ route('calendar.store') }}" method="POST">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Event Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Solution Architecture Demo with Acme Team" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Event Type</label>
                        <select name="event_type" class="form-control">
                            <option value="meeting" selected>🤝 Meeting</option>
                            <option value="call">📞 Phone Call</option>
                            <option value="deadline">⏰ Deal Deadline</option>
                            <option value="reminder">🔔 Reminder</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Client (Optional)</label>
                        <select name="client_id" class="form-control">
                            <option value="">None / General</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Date & Time *</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ now()->addDay()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div>
                    <label class="form-label">Event Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Meeting agenda or notes..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddEventModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Event</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddEventModal() { document.getElementById('addEventModal').style.display = 'flex'; }
    function closeAddEventModal() { document.getElementById('addEventModal').style.display = 'none'; }
</script>
@endpush
@endsection
