<div>
    <style>
        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 100;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 20px 18px;
            text-decoration: none;
        }

        .brand-logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            flex-shrink: 0;
        }

        .brand-name {
            font-family: var(--font-heading);
            font-size: 20px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .nav-section {
            padding: 0 12px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .nav-item a:hover {
            color: var(--text-main);
            background-color: var(--bg-surface-hover);
        }

        .nav-item.active a {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .nav-item.active a svg {
            stroke: #ffffff;
        }

        /* BOTTOM AI ASSISTANT WIDGET */
        .sidebar-ai-widget {
            margin: 12px;
            padding: 16px 14px;
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%);
            border: 1px solid #e0e7ff;
            border-radius: var(--radius-lg);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .ai-robot-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 2px;
        }

        .ai-widget-title {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--text-main);
        }

        .ai-widget-desc {
            font-size: 11.5px;
            color: var(--text-muted);
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .ai-widget-btn {
            width: 100%;
            padding: 7px 12px;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #ffffff;
            border-radius: var(--radius-md);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
            transition: all 0.15s ease;
        }

        .ai-widget-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        /* Mobile Sidebar Drawer Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 99;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>

    <div>
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-logo-icon">
                <i data-lucide="sparkle" style="width: 20px; height: 20px; color: white;"></i>
            </div>
            <span class="brand-name">AI CRM</span>
        </a>

        <!-- Navigation List -->
        <div class="nav-section">
            <ul class="nav-list">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i data-lucide="layout-dashboard" style="width: 18px; height: 18px;"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <a href="{{ route('clients.index') }}" target="_blank">
                        <i data-lucide="user" style="width: 18px; height: 18px;"></i>
                        <span>Clients</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <a href="{{ route('sales.index') }}" target="_blank">
                        <i data-lucide="shopping-cart" style="width: 18px; height: 18px;"></i>
                        <span>Sales</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <a href="{{ route('invoices.index') }}" target="_blank">
                        <i data-lucide="file-text" style="width: 18px; height: 18px;"></i>
                        <span>Invoices</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" target="_blank">
                        <i data-lucide="box" style="width: 18px; height: 18px;"></i>
                        <span>Products</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('user-details*') || request()->routeIs('scrape.*') ? 'active' : '' }}">
                    <a href="{{ route('user-details.list') }}" target="_blank">
                        <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('ai.*') ? 'active' : '' }}">
                    <a href="{{ route('ai.index') }}" target="_blank">
                        <i data-lucide="bot" style="width: 18px; height: 18px; color: #8b5cf6;"></i>
                        <span>AI Assistant</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}" target="_blank">
                        <i data-lucide="settings" style="width: 18px; height: 18px;"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bottom AI Assistant Widget -->
    <div class="sidebar-ai-widget">
        <div class="ai-robot-avatar">
            🤖
        </div>
        <div class="ai-widget-title">AI Assistant</div>
        <div class="ai-widget-desc">Get smart insights about your business data instantly.</div>
        <a href="{{ route('ai.index') }}" target="_blank" class="ai-widget-btn">Ask AI Assistant</a>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }
</script>
