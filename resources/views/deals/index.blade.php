@extends('layouts.app')

@section('title', 'Sales Deals & Pipeline Kanban - CRM Pro')

@section('content')
<style>
    .kanban-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .kanban-board {
        display: grid;
        grid-template-columns: repeat(5, minmax(260px, 1fr));
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 20px;
        min-height: calc(100vh - 220px);
    }

    .kanban-col {
        background-color: var(--bg-surface-hover);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }

    .col-header {
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }

    .col-title-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .col-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .col-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .col-count {
        background-color: var(--bg-surface);
        border: 1px solid var(--border-color);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
    }

    .col-total-val {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        padding: 8px 16px;
        background: rgba(0,0,0,0.02);
        border-bottom: 1px solid var(--border-subtle);
    }

    .cards-container {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        overflow-y: auto;
        flex: 1;
        min-height: 150px;
    }

    .deal-card {
        background-color: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        cursor: grab;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .deal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        border-color: var(--primary);
    }

    .deal-card.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }

    .deal-title {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .deal-client-name {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .deal-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid var(--border-subtle);
    }

    .deal-amount {
        font-family: var(--font-heading);
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .deal-prob {
        font-size: 11px;
        font-weight: 600;
        color: var(--primary);
    }
</style>

<div class="kanban-header">
    <div>
        <h1 class="page-title">Sales Deals & Pipeline Kanban</h1>
        <p class="page-subtitle">Drag and drop opportunities between stages to update your pipeline in real-time.</p>
    </div>

    <div class="header-actions">
        <button type="button" class="btn btn-primary" onclick="openQuickAddModal()">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Add Deal</span>
        </button>
    </div>
</div>

<div class="kanban-board">
    @foreach($stages as $stageKey => $stage)
        <div class="kanban-col" data-stage="{{ $stageKey }}">
            <div class="col-header">
                <div class="col-title-wrap">
                    <span class="col-dot" style="background-color: {{ $stage['dot'] }};"></span>
                    <span class="col-name">{{ $stage['name'] }}</span>
                    <span class="col-count">{{ $stage['deals']->count() }}</span>
                </div>
            </div>

            <div class="col-total-val">
                Total: ${{ number_format($stage['deals']->sum('amount'), 0) }}
            </div>

            <div class="cards-container" ondragover="handleDragOver(event)" ondrop="handleDrop(event, '{{ $stageKey }}')">
                @foreach($stage['deals'] as $deal)
                    <div class="deal-card" draggable="true" ondragstart="handleDragStart(event, {{ $deal->id }})" ondragend="handleDragEnd(event)" id="deal-card-{{ $deal->id }}">
                        <div class="deal-title">{{ $deal->title }}</div>
                        <div class="deal-client-name">
                            <i data-lucide="building-2" style="width: 12px; height: 12px; display: inline-block; vertical-align: -1px;"></i>
                            {{ $deal->client->company ?? ($deal->client->name ?? 'Acme Corp') }}
                        </div>
                        <div class="deal-card-footer">
                            <div class="deal-amount">${{ number_format($deal->amount, 0) }}</div>
                            <div class="deal-prob">{{ $deal->probability }}% win</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    let draggedDealId = null;

    function handleDragStart(e, id) {
        draggedDealId = id;
        e.target.classList.add('dragging');
        e.dataTransfer.setData('text/plain', id);
    }

    function handleDragEnd(e) {
        e.target.classList.remove('dragging');
    }

    function handleDragOver(e) {
        e.preventDefault();
    }

    async function handleDrop(e, targetStage) {
        e.preventDefault();
        if (!draggedDealId) return;

        const card = document.getElementById('deal-card-' + draggedDealId);
        if (card) {
            e.currentTarget.appendChild(card);
        }

        try {
            const res = await fetch(`/deals/${draggedDealId}/stage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ stage: targetStage })
            });
            const data = await res.json();
            if (!data.success) {
                alert('Could not update stage');
            }
        } catch (err) {
            console.error('Error moving deal:', err);
        }
    }
</script>
@endpush
@endsection
