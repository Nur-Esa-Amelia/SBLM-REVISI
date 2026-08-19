<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Prodi - Sistem Early Warning IKU')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Apply theme immediately before paint to prevent flash -->
    <script>
        (function() {
            var theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <style>
        /* ==================== THEME VARIABLES ==================== */
        :root, html[data-theme="dark"] {
            --bg-base:        #090d16;
            --bg-surface:     #0f172a;
            --bg-surface2:    #1e293b;
            --bg-surface3:    #334155;
            --border:         #1e293b;
            --border-muted:   rgba(30,41,59,0.5);
            --text-primary:   #ffffff;
            --text-secondary: #f8fafc;
            --text-muted:     #94a3b8;
            --text-faint:     #64748b;
            --header-bg:      rgba(15,23,42,0.9);
            --scrollbar-track:#0f172a;
            --scrollbar-thumb:#1e293b;
            --scrollbar-hover:#334155;
            --table-th-bg:    rgba(15,23,42,0.4);
            --input-bg:       #1e293b;
            --input-border:   #334155;
            --nav-hover-bg:   rgba(255,255,255,0.04);
            --dropdown-border:rgba(255,255,255,0.05);
            --time-card-bg:   #090d16;
            --tr-hover-bg:    rgba(30,41,59,0.25);
            --pagination-bg:  #1e293b;
            --pagination-border:#334155;
            --pagination-dis: #0f172a;
        }

        html[data-theme="light"] {
            --bg-base:        #f1f5f9;
            --bg-surface:     #ffffff;
            --bg-surface2:    #f8fafc;
            --bg-surface3:    #e2e8f0;
            --border:         #e2e8f0;
            --border-muted:   rgba(226,232,240,0.7);
            --text-primary:   #0f172a;
            --text-secondary: #1e293b;
            --text-muted:     #475569;
            --text-faint:     #64748b;
            --header-bg:      rgba(255,255,255,0.95);
            --scrollbar-track:#f1f5f9;
            --scrollbar-thumb:#cbd5e1;
            --scrollbar-hover:#94a3b8;
            --table-th-bg:    rgba(248,250,252,0.8);
            --input-bg:       #f8fafc;
            --input-border:   #cbd5e1;
            --nav-hover-bg:   rgba(0,0,0,0.04);
            --dropdown-border:rgba(0,0,0,0.06);
            --time-card-bg:   #f1f5f9;
            --tr-hover-bg:    rgba(226,232,240,0.5);
            --pagination-bg:  #f1f5f9;
            --pagination-border:#e2e8f0;
            --pagination-dis: #ffffff;
        }

        /* ==================== THEME TOGGLE BUTTON ==================== */
        .theme-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-surface);
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .theme-toggle-btn:hover {
            background: var(--bg-surface2);
            color: var(--text-primary);
            border-color: var(--bg-surface3);
        }
        .theme-toggle-btn svg { width: 18px; height: 18px; }
        html[data-theme="light"] .icon-moon { display: none; }
        html[data-theme="dark"]  .icon-sun  { display: none; }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-secondary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        /* Container Layout */
        .admin-container {
            display: flex;
            width: 100vw;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            flex-shrink: 0;
            z-index: 45;
        }

        .sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: #3b82f6;
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow-y: auto;
        }

        /* Sleek custom scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--scrollbar-thumb);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-hover);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background-color: var(--nav-hover-bg);
        }

        .nav-link.active {
            color: var(--text-primary) !important;
            background-color: #2563eb !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Sidebar Dropdown Styling */
        .sidebar-dropdown {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 14px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            background: transparent;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            outline: none;
        }

        .sidebar-dropdown-toggle:hover {
            color: var(--text-primary);
            background-color: var(--nav-hover-bg);
        }

        .sidebar-dropdown-toggle.active {
            color: var(--text-primary);
            background-color: var(--nav-hover-bg);
        }

        .sidebar-dropdown-toggle .toggle-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-dropdown-toggle .toggle-content svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .sidebar-dropdown-toggle .chevron-icon {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
        }

        .sidebar-dropdown-toggle.open .chevron-icon {
            transform: rotate(180deg);
        }

        .sidebar-dropdown-menu {
            display: none;
            flex-direction: column;
            gap: 4px;
            padding-left: 36px;
            margin-top: 2px;
        }

        .sidebar-dropdown-menu.show {
            display: flex;
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            font-size: 0.825rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            border-bottom: 1px solid var(--dropdown-border);
            transition: all 0.2s ease;
        }

        .dropdown-link:last-child {
            border-bottom: none;
        }

        .dropdown-link svg {
            width: 14px;
            height: 14px;
            color: var(--text-faint);
            transition: color 0.2s ease;
            flex-shrink: 0;
        }

        .dropdown-link:hover {
            color: var(--text-primary);
            background-color: var(--nav-hover-bg);
        }

        .dropdown-link:hover svg {
            color: var(--text-primary);
        }

        .dropdown-link.active {
            color: var(--text-primary) !important;
            background-color: rgba(37, 99, 235, 0.15) !important;
            border-left: 2px solid #2563eb;
            border-radius: 0 6px 6px 0;
            padding-left: 10px;
        }

        .dropdown-link.active svg {
            color: #3b82f6;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        /* Workspace Layout */
        .main-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            height: 70px;
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 30;
            backdrop-filter: blur(8px);
            flex-shrink: 0;
        }

        .header-title-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-toggle-btn {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
        }

        .menu-toggle-btn:hover {
            background-color: var(--bg-surface2);
            color: var(--text-primary);
        }

        .page-title-group {
            display: flex;
            flex-direction: column;
        }

        .page-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .page-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .user-profile-panel {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            cursor: pointer;
        }

        .user-profile-panel:hover .user-avatar {
            transform: scale(1.08);
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.4);
            border-color: #3b82f6;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 0.7rem;
            color: #38bdf8;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2);
            color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }

        /* Body container */
        .main-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            flex: 1;
            overflow-y: auto;
        }

        /* Cards and Components */
        .card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .welcome-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            position: relative;
            overflow: hidden;
        }

        .welcome-text {
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 2;
        }

        .welcome-badge {
            align-self: flex-start;
            padding: 4px 10px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #38bdf8;
            background-color: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .welcome-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .welcome-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.5;
            max-width: 580px;
        }

        .system-time-card {
            background-color: var(--time-card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
            flex-shrink: 0;
        }

        .time-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            color: #38bdf8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .time-text {
            display: flex;
            flex-direction: column;
        }

        .time-label {
            font-size: 0.65rem;
            color: var(--text-faint);
        }

        .time-value {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Responsive Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .stat-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            border-color: rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .stat-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.target {
            background-color: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }

        .stat-icon.realisasi {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .stat-icon.tercapai {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .stat-icon.belum-tercapai {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .stat-footer {
            padding-top: 12px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-desc {
            font-size: 0.75rem;
            color: var(--text-faint);
        }

        .stat-link {
            font-size: 0.75rem;
            font-weight: 700;
            color: #3b82f6;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-link:hover {
            color: #ffffff;
        }

        /* Buttons styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
            outline: none;
        }

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: transparent;
            border: 1px solid var(--bg-surface3);
            color: var(--text-muted);
        }

        .btn-secondary:hover {
            background-color: var(--bg-surface2);
            color: var(--text-primary);
            border-color: var(--text-faint);
        }

        .btn-rose {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .btn-rose:hover {
            background-color: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        .btn-action-edit {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 0 !important;
            border-radius: 10px;
            border: 1px solid rgba(56, 189, 248, 0.25) !important;
            background-color: rgba(56, 189, 248, 0.05) !important;
            color: #38bdf8 !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-action-edit:hover {
            border-color: #38bdf8 !important;
            background-color: rgba(56, 189, 248, 0.15) !important;
            color: #ffffff !important;
        }

        .btn-action-delete {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 0 !important;
            border-radius: 10px;
            border: 1px solid rgba(239, 68, 68, 0.25) !important;
            background-color: rgba(239, 68, 68, 0.05) !important;
            color: #f87171 !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-action-delete:hover {
            border-color: #ef4444 !important;
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #ffffff !important;
        }

        /* Toast boxes */
        .alert-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* Forms Styling */
        .form-layout-container {
            max-width: 580px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group-custom {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.01em;
        }

        .form-input-custom {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .form-input-custom:focus {
            border-color: #3b82f6;
            background-color: var(--input-bg);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-input-custom::placeholder {
            color: var(--text-faint);
        }

        .form-select-custom {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .form-select-custom:focus {
            border-color: #3b82f6;
            background-color: var(--input-bg);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-error-custom {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 4px;
            font-weight: 500;
        }

        .form-footer-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            margin-top: 8px;
        }

        /* Filter rows */
        .filter-row-custom {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-item-custom {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 180px;
        }

        /* Table custom designs */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table-custom th {
            padding: 14px 20px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
            background-color: var(--table-th-bg);
        }

        .table-custom td {
            padding: 14px 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-muted);
            vertical-align: middle;
        }

        .table-custom tr:hover {
            background-color: var(--tr-hover-bg);
        }

        /* Badges */
        .badge-custom {
            display: inline-flex;
            padding: 2px 8px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 4px;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
        }

        .badge-purple {
            background-color: rgba(168, 85, 247, 0.1);
            border-color: rgba(168, 85, 247, 0.2);
            color: #c084fc;
        }

        .badge-blue {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .badge-green {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .badge-rose {
            background-color: rgba(244, 63, 94, 0.1);
            border-color: rgba(244, 63, 94, 0.2);
            color: #fb7185;
        }

        .badge-gray {
            background-color: rgba(100, 116, 139, 0.1);
            border-color: rgba(100, 116, 139, 0.2);
            color: #94a3b8;
        }

        /* Responsive rules */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            #sidebar-overlay.open {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .welcome-card {
                flex-direction: column;
                align-items: stretch;
            }
            .top-header {
                padding: 0 16px;
            }
            .main-body {
                padding: 16px;
            }
            .filter-row-custom {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Laravel Pagination CSS Fix */
        nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-top: 10px;
        }

        nav[role="navigation"] p {
            font-size: 0.8rem;
            color: #64748b;
        }

        nav[role="navigation"] div:last-child {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        nav[role="navigation"] a.relative,
        nav[role="navigation"] span[aria-current="page"] > span,
        nav[role="navigation"] span[aria-disabled="true"] > span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #cbd5e1;
            margin: 0;
            transition: all 0.2s ease;
        }

        nav[role="navigation"] a.relative:hover {
            background-color: #334155;
            color: #ffffff;
            border-color: #475569;
        }

        nav[role="navigation"] span[aria-current="page"] > span {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            cursor: default;
        }

        nav[role="navigation"] span[aria-disabled="true"] > span {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #475569 !important;
            opacity: 0.5;
            cursor: not-allowed;
        }

        nav[role="navigation"] svg {
            width: 14px;
            height: 14px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (mobileMenuBtn && sidebar && sidebarOverlay) {
                mobileMenuBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                    sidebarOverlay.classList.toggle('open');
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('open');
                });
            }

            // Sidebar dropdown toggle (Accordion style)
            const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                const parent = toggle.closest('.sidebar-dropdown');
                const menu = parent.querySelector('.sidebar-dropdown-menu');
                if (menu.classList.contains('show')) {
                    toggle.classList.add('open');
                }

                toggle.addEventListener('click', () => {
                    const isOpen = toggle.classList.contains('open');
                    
                    // Close all other dropdowns
                    dropdownToggles.forEach(otherToggle => {
                        if (otherToggle !== toggle) {
                            otherToggle.classList.remove('open');
                            const otherParent = otherToggle.closest('.sidebar-dropdown');
                            const otherMenu = otherParent.querySelector('.sidebar-dropdown-menu');
                            otherMenu.classList.remove('show');
                        }
                    });

                    // Toggle current dropdown
                    if (isOpen) {
                        toggle.classList.remove('open');
                        menu.classList.remove('show');
                    } else {
                        toggle.classList.add('open');
                        menu.classList.add('show');
                    }
                });
            });

            // ===== Theme Toggle =====
            const themeBtn = document.getElementById('theme-toggle-btn');
            if (themeBtn) {
                themeBtn.addEventListener('click', () => {
                    const current = document.documentElement.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('theme', next);
                });
            }
        });
    </script>
</head>
<body>

    <div class="admin-container">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebar-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 40; display: none;"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <!-- Sidebar Brand/Logo -->
            <div class="sidebar-header">
                <h1 class="sidebar-title">Sistem IKU</h1>
                <span class="sidebar-subtitle">
                    @if(auth()->user()->role === 'admin_p2mp')
                        Admin P2MP
                    @else
                        {{ auth()->user()->prodi ? auth()->user()->prodi->nama_prodi : 'Program Studi' }}
                    @endif
                </span>
            </div>

            <!-- Sidebar Navigation -->
            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                @if(auth()->user()->role === 'admin_p2mp')
                    <!-- Dashboard -->
                    <a href="{{ route('adminp2mp.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Data Master Dropdown -->
                    <div class="sidebar-dropdown">
                        <button type="button" class="sidebar-dropdown-toggle {{ (request()->routeIs('adminp2mp.prodi.*') || request()->routeIs('adminp2mp.users.*') || request()->routeIs('adminprodi.kategori.*') || request()->routeIs('adminprodi.iku.*') || request()->routeIs('adminprodi.bukti.*')) ? 'active' : '' }}">
                            <div class="toggle-content">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                <span>Data Master</span>
                            </div>
                            <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="sidebar-dropdown-menu {{ (request()->routeIs('adminp2mp.prodi.*') || request()->routeIs('adminp2mp.users.*') || request()->routeIs('adminprodi.kategori.*') || request()->routeIs('adminprodi.iku.*') || request()->routeIs('adminprodi.bukti.*')) ? 'show' : '' }}">
                            <a href="{{ route('adminp2mp.prodi.index') }}" class="dropdown-link {{ request()->routeIs('adminp2mp.prodi.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Kelola Program Studi
                            </a>
                            <a href="{{ route('adminp2mp.users.index') }}" class="dropdown-link {{ request()->routeIs('adminp2mp.users.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Kelola User
                            </a>
                            <a href="{{ route('adminprodi.kategori.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.kategori.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                Kategori IKU
                            </a>
                            <a href="{{ route('adminprodi.iku.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.iku.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Data IKU
                            </a>
                            <a href="{{ route('adminprodi.bukti.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.bukti.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Jenis Bukti
                            </a>
                        </div>
                    </div>

                    <!-- Validasi Bukti IKU -->
                    <a href="{{ route('adminp2mp.validasi') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.validasi') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Validasi Bukti IKU
                    </a>

                    <!-- Laporan & Pengaturan Dropdown -->
                    <div class="sidebar-dropdown">
                        <button type="button" class="sidebar-dropdown-toggle {{ (request()->routeIs('adminp2mp.monitoring') || request()->routeIs('adminprodi.laporan.index') || request()->routeIs('adminprodi.pengaturan.*')) ? 'active' : '' }}">
                            <div class="toggle-content">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                </svg>
                                <span>Laporan & Pengaturan</span>
                            </div>
                            <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="sidebar-dropdown-menu {{ (request()->routeIs('adminp2mp.monitoring') || request()->routeIs('adminprodi.laporan.index') || request()->routeIs('adminprodi.pengaturan.*')) ? 'show' : '' }}">
                            <a href="{{ route('adminp2mp.monitoring') }}" class="dropdown-link {{ request()->routeIs('adminp2mp.monitoring') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Monitor & Laporan
                            </a>
                            <a href="{{ route('adminprodi.laporan.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.laporan.index') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Laporan Prodi
                            </a>
                            <a href="{{ route('adminprodi.pengaturan.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.pengaturan.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Pengaturan System
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Dashboard -->
                    <a href="{{ route('adminprodi.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('adminprodi.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Data Master Dropdown -->
                    <div class="sidebar-dropdown">
                        <button type="button" class="sidebar-dropdown-toggle {{ (request()->routeIs('adminprodi.kategori.*') || request()->routeIs('adminprodi.iku.*') || request()->routeIs('adminprodi.bukti.*') || request()->routeIs('adminprodi.dosen')) ? 'active' : '' }}">
                            <div class="toggle-content">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                <span>Data Master</span>
                            </div>
                            <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="sidebar-dropdown-menu {{ (request()->routeIs('adminprodi.kategori.*') || request()->routeIs('adminprodi.iku.*') || request()->routeIs('adminprodi.bukti.*') || request()->routeIs('adminprodi.dosen')) ? 'show' : '' }}">
                            <a href="{{ route('adminprodi.kategori.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.kategori.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                Kategori IKU
                            </a>
                            <a href="{{ route('adminprodi.iku.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.iku.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Data IKU
                            </a>
                            <a href="{{ route('adminprodi.bukti.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.bukti.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Jenis Bukti
                            </a>
                            <a href="{{ route('adminprodi.dosen') }}" class="dropdown-link {{ request()->routeIs('adminprodi.dosen') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Data Dosen
                            </a>
                        </div>
                    </div>

                    <!-- Target & Penugasan Dropdown -->
                    <div class="sidebar-dropdown">
                        <button type="button" class="sidebar-dropdown-toggle {{ (request()->routeIs('adminprodi.pencapaian.*') || request()->routeIs('adminprodi.penugasan.*') || request()->routeIs('adminprodi.bukti-dosen')) ? 'active' : '' }}">
                            <div class="toggle-content">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Target & Penugasan</span>
                            </div>
                            <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="sidebar-dropdown-menu {{ (request()->routeIs('adminprodi.pencapaian.*') || request()->routeIs('adminprodi.penugasan.*') || request()->routeIs('adminprodi.bukti-dosen')) ? 'show' : '' }}">
                            <a href="{{ route('adminprodi.pencapaian.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.pencapaian.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                Target IKU Tahunan
                            </a>
                            <a href="{{ route('adminprodi.penugasan.index') }}" class="dropdown-link {{ request()->routeIs('adminprodi.penugasan.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                Penugasan Dosen
                            </a>
                            <a href="{{ route('adminprodi.bukti-dosen') }}" class="dropdown-link {{ request()->routeIs('adminprodi.bukti-dosen') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Bukti IKU Dosen
                            </a>
                        </div>
                    </div>

                    <!-- Laporan Prodi -->
                    <a href="{{ route('adminprodi.laporan.index') }}" 
                       class="nav-link {{ request()->routeIs('adminprodi.laporan.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Laporan Prodi
                    </a>
                @endif
            </nav>

            <!-- Sidebar Logout -->
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                    @csrf
                    <button type="submit" class="btn btn-rose" style="width: 100%; font-size: 0.75rem; padding: 8px 12px;">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Keluar Sistem
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="main-content">
            <!-- Top bar Header -->
            <header class="top-header">
                <div class="header-title-area">
                    <button id="mobile-menu-btn" class="menu-toggle-btn">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="page-title-group">
                        <h2 class="page-title">@yield('page_title', 'Dashboard')</h2>
                        <span class="page-subtitle">@yield('page_subtitle', 'Sistem Early Warning IKU')</span>
                    </div>
                </div>

                <!-- Theme Toggle + Profile -->
                <div style="display:flex;align-items:center;gap:12px;">
                    <button id="theme-toggle-btn" class="theme-toggle-btn" title="Toggle Tema Gelap/Terang" aria-label="Toggle tema">
                        <!-- Moon icon (shown in dark mode) -->
                        <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
                        </svg>
                        <!-- Sun icon (shown in light mode) -->
                        <svg class="icon-sun" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                        </svg>
                    </button>
                    <a href="{{ route('profile') }}" class="user-profile-panel">
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">{{ auth()->user()->role === 'kaprodi' ? 'Kaprodi' : 'Admin Prodi' }}</span>
                        </div>
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    </a>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="main-body">
                <!-- Toast Notifications -->
                @if(session('success'))
                    <div class="alert-box alert-success" role="alert">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-box alert-danger" role="alert">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if($errors->any() && !request()->routeIs('*.store') && !request()->routeIs('*.update'))
                    <div class="alert-box alert-danger" role="alert">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <ul style="list-style-type: none; margin: 0; padding: 0;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
