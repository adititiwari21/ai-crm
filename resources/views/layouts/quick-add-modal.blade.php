<!-- Quick Add Modal -->
<div class="modal-backdrop" id="quickAddModal">
    <div class="modal-box card-p">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Quick Create</h3>
            </div>
            <button type="button" onclick="closeQuickAddModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <!-- Tabs -->
        <div style="display: flex; gap: 8px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-bottom: 20px;">
            <button type="button" class="btn btn-sm btn-primary" id="tabBtnClient" onclick="switchQuickTab('client')">Client</button>
            <button type="button" class="btn btn-sm btn-secondary" id="tabBtnInvoice" onclick="switchQuickTab('invoice')">Invoice</button>
            <button type="button" class="btn btn-sm btn-secondary" id="tabBtnTask" onclick="switchQuickTab('task')">Task</button>
            <button type="button" class="btn btn-sm btn-secondary" id="tabBtnScrape" onclick="switchQuickTab('scrape')">AI Scrape</button>
        </div>

        <!-- FORM 1: NEW CLIENT -->
        <form action="{{ route('clients.store') }}" method="POST" id="quickFormClient">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Client / Contact Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Jane Doe" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="jane@company.com">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+1 (555) 000-0000">
                    </div>
                </div>
                <div>
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company" class="form-control" placeholder="Acme Inc.">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeQuickAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Client</button>
                </div>
            </div>
        </form>

        <!-- FORM 2: NEW INVOICE -->
        <form action="{{ route('invoices.store') }}" method="POST" id="quickFormInvoice" style="display: none;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Client *</label>
                        <select name="client_id" class="form-control" required>
                            <option value="">Select Client...</option>
                            @foreach(\App\Models\Client::all() as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" value="INV-{{ date('Y') }}-{{ strtoupper(substr(md5(uniqid()), 0, 4)) }}" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Amount ($) *</label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="4500" required>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ now()->addDays(14)->format('Y-m-d') }}">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeQuickAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Invoice</button>
                </div>
            </div>
        </form>

        <!-- FORM 3: NEW TASK -->
        <form action="{{ route('tasks.store') }}" method="POST" id="quickFormTask" style="display: none;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Task Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Follow up on proposal with CFO" required>
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
                <div>
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeQuickAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </div>
        </form>

        <!-- FORM 4: AI WEBSITE SCRAPE -->
        <form action="{{ route('scrape.company') }}" method="POST" id="quickFormScrape" style="display: none;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Target Website URL *</label>
                    <input type="url" name="website" class="form-control" placeholder="https://company.com" required>
                    <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px; display: block;">
                        AI will automatically extract company summary, tech stack, lead score, and pitch.
                    </span>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeQuickAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
                        <span>Scrape & Analyze</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openQuickAddModal() {
        document.getElementById('quickAddModal').style.display = 'flex';
    }

    function closeQuickAddModal() {
        document.getElementById('quickAddModal').style.display = 'none';
    }

    function switchQuickTab(tab) {
        const tabs = ['client', 'invoice', 'task', 'scrape'];
        tabs.forEach(t => {
            const form = document.getElementById('quickForm' + t.charAt(0).toUpperCase() + t.slice(1));
            const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
            if (t === tab) {
                form.style.display = 'block';
                btn.className = 'btn btn-sm btn-primary';
            } else {
                form.style.display = 'none';
                btn.className = 'btn btn-sm btn-secondary';
            }
        });
    }

    document.getElementById('quickAddModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('quickAddModal')) {
            closeQuickAddModal();
        }
    });
</script>
