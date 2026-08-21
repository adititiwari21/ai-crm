<header class="top-navbar">
    <style>
        .top-navbar {
            height: 64px;
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            max-width: 440px;
        }

        .btn-hamburger {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            transition: all 0.15s ease;
        }

        .btn-hamburger:hover {
            color: var(--text-main);
            background-color: var(--bg-surface-hover);
        }

        .navbar-search-box {
            display: flex;
            align-items: center;
            background-color: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0 14px;
            width: 100%;
            height: 38px;
            gap: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .navbar-search-box:hover {
            border-color: #cbd5e1;
            background-color: #ffffff;
        }

        .navbar-search-input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            color: var(--text-main);
            width: 100%;
            cursor: pointer;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-navbar-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: transparent;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }

        .btn-navbar-icon:hover {
            color: var(--text-main);
            background-color: var(--bg-surface-hover);
            border-color: var(--border-color);
        }

        .navbar-bell-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background-color: #8b5cf6;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-surface);
        }

        /* USER PROFILE CHIP */
        .navbar-user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 10px 4px 4px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.15s ease;
            background-color: transparent;
        }

        .navbar-user-chip:hover {
            background-color: var(--bg-surface-hover);
        }

        .user-avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .user-info-stack {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            text-align: left;
        }

        .user-name-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
        }

        .user-role-sub {
            font-size: 11px;
            color: var(--text-muted);
        }
    </style>

    <div class="navbar-left">
        <button type="button" class="btn-hamburger" onclick="toggleSidebar()">
            <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
        </button>

        <!-- Search Bar matching Screenshot -->
        <div class="navbar-search-box" onclick="openCommandPalette()">
            <i data-lucide="search" style="width: 15px; height: 15px; color: var(--text-muted);"></i>
            <input type="text" class="navbar-search-input" placeholder="Search anything..." readonly>
        </div>
    </div>

    <div class="navbar-right">
        <!-- Notification Bell with badge 5 -->
        <button type="button" class="btn-navbar-icon" title="Notifications">
            <i data-lucide="bell" style="width: 18px; height: 18px;"></i>
            <span class="navbar-bell-badge">5</span>
        </button>

        <!-- User Profile -->
        <div class="navbar-user-chip" onclick="openEditProfileModal()" title="Click to edit profile">
            <div class="user-avatar-circle">{{ $crmSetting->admin_initials ?? 'A' }}</div>
            <div class="user-info-stack">
                <span class="user-name-title">{{ $crmSetting->admin_name ?? 'Admin User' }}</span>
                <span class="user-role-sub">{{ $crmSetting->admin_role ?? 'Administrator' }}</span>
            </div>
            <i data-lucide="chevron-down" style="width: 12px; height: 12px; color: var(--text-muted); margin-left: 2px;"></i>
        </div>
    </div>
</header>

<!-- Edit Profile Modal -->
<div class="modal-backdrop" id="editProfileModal">
    <div class="modal-box card-p" style="max-width: 440px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="user-avatar-circle" style="width: 38px; height: 38px; font-size: 14.5px;">
                    {{ $crmSetting->admin_initials ?? 'A' }}
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main); line-height: 1.2;">Edit Your Profile</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Update your name and role in AI CRM</span>
                </div>
            </div>
            <button type="button" onclick="closeEditProfileModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>

        <form action="{{ route('settings.profile') }}" method="POST">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Your Full Name *</label>
                    <input type="text" name="admin_name" class="form-control" value="{{ $crmSetting->admin_name ?? 'Admin User' }}" placeholder="e.g. Admin User" required autofocus>
                </div>

                <div>
                    <label class="form-label">Your Role / Designation</label>
                    <input type="text" name="admin_role" class="form-control" value="{{ $crmSetting->admin_role ?? 'Administrator' }}" placeholder="e.g. Administrator">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                    <a href="{{ route('settings.index') }}" style="font-size: 12.5px; color: var(--primary); font-weight: 600; text-decoration: none;">
                        Open All Settings →
                    </a>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="closeEditProfileModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Profile</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditProfileModal() {
        document.getElementById('editProfileModal').style.display = 'flex';
    }

    function closeEditProfileModal() {
        document.getElementById('editProfileModal').style.display = 'none';
    }

    document.getElementById('editProfileModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('editProfileModal')) {
            closeEditProfileModal();
        }
    });
</script>
