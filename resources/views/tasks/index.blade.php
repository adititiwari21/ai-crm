@extends('layouts.app')

@section('title', 'Task Management - CRM Pro')

@section('content')
<style>
    .task-stat-banner {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) {
        .task-stat-banner {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Tasks & Follow-ups</h1>
        <p class="page-subtitle">Track sales actions, team deliverables, and customer follow-up deadlines.</p>
    </div>

    <div>
        <button type="button" class="btn btn-primary" onclick="openAddTaskModal()">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Add Task</span>
        </button>
    </div>
</div>

<!-- Task Stat Banner -->
<div class="task-stat-banner">
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">To Do</div>
            <div class="stat-value-text" style="color: #3b82f6;">{{ $todoCount }}</div>
        </div>
        <div class="stat-icon-wrapper" style="background-color: #dbeafe; color: #1d4ed8;"><i data-lucide="list-todo" style="width: 20px; height: 20px;"></i></div>
    </div>
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">In Progress</div>
            <div class="stat-value-text" style="color: #f59e0b;">{{ $inProgressCount }}</div>
        </div>
        <div class="stat-icon-wrapper stat-icon-orange"><i data-lucide="clock" style="width: 20px; height: 20px;"></i></div>
    </div>
    <div class="card stat-card-inner">
        <div>
            <div class="stat-label-text">Completed</div>
            <div class="stat-value-text" style="color: var(--success);">{{ $completedCount }}</div>
        </div>
        <div class="stat-icon-wrapper stat-icon-green"><i data-lucide="check-circle-2" style="width: 20px; height: 20px;"></i></div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Linked Client</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $t)
                    @php
                        $priClass = match($t->priority) {
                            'High' => 'badge-danger',
                            'Medium' => 'badge-warning',
                            'Low' => 'badge-blue',
                            default => 'badge-blue'
                        };
                        $statusClass = match($t->status) {
                            'Completed' => 'badge-paid',
                            'In Progress' => 'badge-negotiation',
                            default => 'badge-new'
                        };
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $t->title }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $t->description ?: 'No details' }}</div>
                        </td>
                        <td>{{ $t->client->company ?? ($t->client->name ?? 'Internal / General') }}</td>
                        <td><span class="badge {{ $priClass }}">{{ $t->priority }}</span></td>
                        <td>{{ $t->due_date ? $t->due_date->format('M d, Y') : 'No date' }}</td>
                        <td>
                            <form action="{{ route('tasks.status', $t->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <select name="status" class="form-control" style="padding: 4px 8px; font-size: 12px; border-radius: 12px; width: auto;" onchange="this.form.submit()">
                                    <option value="To Do" {{ $t->status === 'To Do' ? 'selected' : '' }}>To Do</option>
                                    <option value="In Progress" {{ $t->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ $t->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">
                                    <i data-lucide="trash" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No tasks created yet. Click "Add Task" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Task -->
<div class="modal-backdrop" id="addTaskModal">
    <div class="modal-box card-p">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Add New Task</h3>
            <button type="button" onclick="closeAddTaskModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Task Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Schedule discovery call with VP of Product" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="High">High</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Initial Status</label>
                        <select name="status" class="form-control">
                            <option value="To Do" selected>To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="form-label">Linked Client (Optional)</label>
                        <select name="client_id" class="form-control">
                            <option value="">None / General</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Task Notes / Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Additional details or instructions..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddTaskModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddTaskModal() { document.getElementById('addTaskModal').style.display = 'flex'; }
    function closeAddTaskModal() { document.getElementById('addTaskModal').style.display = 'none'; }
</script>
@endpush
@endsection
