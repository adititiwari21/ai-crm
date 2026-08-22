@extends('layouts.app')

@section('title', 'Clients Hub - CRM Pro')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Client Accounts</h1>
        <p class="page-subtitle">Manage customer relationships, lifetime value, and active opportunities.</p>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="button" class="btn btn-primary" onclick="openQuickAddModal()">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Add Client</span>
        </button>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Company</th>
                    <th>Contact Info</th>
                    <th>Open Deals</th>
                    <th>Lifetime Revenue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $c)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                    {{ substr($c->name, 0, 2) }}
                                </div>
                                <div>
                                    <a href="{{ route('clients.show', $c->id) }}" style="font-weight: 700; color: var(--text-main); text-decoration: none;">{{ $c->name }}</a>
                                    <div style="font-size: 11.5px; color: var(--text-muted);">Joined {{ $c->created_at ? $c->created_at->format('M Y') : 'Recent' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 600; color: var(--text-main);">{{ $c->company ?: 'N/A' }}</td>
                        <td>
                            <div style="font-size: 13px; color: var(--text-main);">{{ $c->email }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $c->phone ?: 'No phone' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ $c->deals->count() }} Deals (${{ number_format($c->open_deals_value, 0) }})</span>
                        </td>
                        <td style="font-family: var(--font-heading); font-weight: 800; color: var(--success); font-size: 15px;">
                            ${{ number_format($c->lifetime_value, 0) }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a href="{{ route('clients.show', $c->id) }}" class="btn btn-secondary btn-sm" title="View 360° Profile">
                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                    <span>Profile</span>
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" title="Quick Edit Client" onclick="openEditClientModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ addslashes($c->company ?? '') }}', '{{ addslashes($c->email ?? '') }}', '{{ addslashes($c->phone ?? '') }}')">
                                    <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                                </button>
                                <form action="/clients/{{ $c->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete client {{ addslashes($c->name) }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);" title="Delete Client">
                                        <i data-lucide="trash" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No clients found. Click "Add Client" to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Quick Edit Client -->
<div class="modal-backdrop" id="editClientModal" style="display: none;">
    <div class="modal-box card-p">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #4f46e5, #3b82f6); display: flex; align-items: center; justify-content: center; color: white;">
                    <i data-lucide="edit-3" style="width: 18px; height: 18px;"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Edit Client Account</h3>
            </div>
            <button type="button" onclick="closeEditClientModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <form action="" method="POST" id="editClientForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="from_index" value="1">

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Client Name *</label>
                    <input type="text" name="name" id="editClientName" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company" id="editClientCompany" class="form-control" placeholder="e.g. Acme Corp">
                </div>

                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" id="editClientEmail" class="form-control" placeholder="client@company.com">
                </div>

                <div>
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="editClientPhone" class="form-control" placeholder="+1 (555) 000-0000">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditClientModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditClientModal(id, name, company, email, phone) {
        document.getElementById('editClientForm').action = '/clients/' + id;
        document.getElementById('editClientName').value = name;
        document.getElementById('editClientCompany').value = company || '';
        document.getElementById('editClientEmail').value = email || '';
        document.getElementById('editClientPhone').value = phone || '';
        document.getElementById('editClientModal').style.display = 'flex';
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function closeEditClientModal() {
        document.getElementById('editClientModal').style.display = 'none';
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('editClientModal');
        if (e.target === modal) {
            closeEditClientModal();
        }
    });
</script>
@endpush
@endsection