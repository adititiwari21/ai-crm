<div class="modal-backdrop" id="commandPaletteModal" style="align-items: flex-start; padding-top: 15vh;">
    <style>
        .palette-box {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 580px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: modalPop 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .palette-input-wrap {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            gap: 12px;
        }

        .palette-input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 15px;
            color: var(--text-main);
            font-weight: 500;
        }

        .palette-results {
            max-height: 320px;
            overflow-y: auto;
            padding: 8px;
        }

        .palette-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            color: var(--text-main);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.1s ease;
            cursor: pointer;
        }

        .palette-item:hover {
            background-color: var(--bg-surface-hover);
            color: var(--primary);
        }
    </style>

    <div class="palette-box">
        <div class="palette-input-wrap">
            <i data-lucide="search" style="width: 18px; height: 18px; color: var(--text-muted);"></i>
            <input type="text" id="paletteSearchInput" class="palette-input" placeholder="Type a command or jump to page..." autofocus>
            <span style="font-size: 11px; background: var(--bg-body); border: 1px solid var(--border-color); padding: 2px 6px; border-radius: 4px; color: var(--text-muted);">ESC</span>
        </div>

        <div class="palette-results" id="paletteResults">
            <a href="{{ route('dashboard') }}" class="palette-item">
                <i data-lucide="layout-dashboard" style="width: 16px; height: 16px; color: var(--primary);"></i>
                <span>Dashboard Overview</span>
            </a>
            <a href="{{ route('clients.index') }}" class="palette-item">
                <i data-lucide="users" style="width: 16px; height: 16px; color: #6366f1;"></i>
                <span>Manage Clients Hub</span>
            </a>
            <a href="{{ route('invoices.index') }}" class="palette-item">
                <i data-lucide="file-text" style="width: 16px; height: 16px; color: #f59e0b;"></i>
                <span>Invoices & Billing</span>
            </a>
            <a href="{{ route('ai.index') }}" class="palette-item">
                <i data-lucide="sparkles" style="width: 16px; height: 16px; color: #8b5cf6;"></i>
                <span>Ask AI Copilot Assistant</span>
            </a>
            <a href="{{ route('tasks.index') }}" class="palette-item">
                <i data-lucide="check-square" style="width: 16px; height: 16px; color: #3b82f6;"></i>
                <span>Task Management</span>
            </a>
            <a href="{{ route('reports.index') }}" class="palette-item">
                <i data-lucide="bar-chart-2" style="width: 16px; height: 16px; color: #10b981;"></i>
                <span>Analytics & Reports</span>
            </a>
            <a href="{{ route('user-details.list') }}" class="palette-item">
                <i data-lucide="zap" style="width: 16px; height: 16px; color: #06b6d4;"></i>
                <span>AI Leads Scraper</span>
            </a>
            <a href="{{ route('settings.index') }}" class="palette-item">
                <i data-lucide="settings" style="width: 16px; height: 16px; color: var(--text-muted);"></i>
                <span>CRM Pro Settings</span>
            </a>
        </div>
    </div>
</div>

<script>
    function openCommandPalette() {
        const modal = document.getElementById('commandPaletteModal');
        modal.style.display = 'flex';
        setTimeout(() => document.getElementById('paletteSearchInput').focus(), 50);
    }

    function closeCommandPalette() {
        document.getElementById('commandPaletteModal').style.display = 'none';
    }

    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            const modal = document.getElementById('commandPaletteModal');
            if (modal.style.display === 'flex') {
                closeCommandPalette();
            } else {
                openCommandPalette();
            }
        }
        if (e.key === 'Escape') {
            closeCommandPalette();
        }
    });

    document.getElementById('commandPaletteModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('commandPaletteModal')) {
            closeCommandPalette();
        }
    });
</script>
