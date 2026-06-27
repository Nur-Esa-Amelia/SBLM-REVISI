<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin P2MP - Sistem Early Warning IKU')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #090d16;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
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
            background-color: #0f172a;
            border-right: 1px solid #1e293b;
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
            border-bottom: 1px solid #1e293b;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            overflow-y: auto;
        }

        .nav-category {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .category-label {
            padding: 0 12px;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.04);
        }

        .nav-link.active {
            color: #ffffff !important;
            background-color: #2563eb !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #1e293b;
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
            background-color: rgba(15, 23, 42, 0.9);
            border-bottom: 1px solid #1e293b;
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
            color: #94a3b8;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
        }

        .menu-toggle-btn:hover {
            background-color: #1e293b;
            color: #ffffff;
        }

        .page-title-group {
            display: flex;
            flex-direction: column;
        }

        .page-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
        }

        .page-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
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
            color: #ffffff;
        }

        .user-role {
            font-size: 0.7rem;
            color: #94a3b8;
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
            background-color: #0f172a;
            border: 1px solid #1e293b;
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
            color: #ffffff;
        }

        .welcome-desc {
            font-size: 0.875rem;
            color: #94a3b8;
            line-height: 1.5;
            max-width: 580px;
        }

        .system-time-card {
            background-color: #090d16;
            border: 1px solid #1e293b;
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
            color: #64748b;
        }

        .time-value {
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
        }

        /* Responsive Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .stat-card {
            background-color: #0f172a;
            border: 1px solid #1e293b;
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
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #ffffff;
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

        .stat-icon.user {
            background-color: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            color: #38bdf8;
        }

        .stat-icon.prodi {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .stat-icon.validasi {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .stat-icon.report {
            background-color: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }

        .stat-footer {
            padding-top: 12px;
            border-top: 1px solid #1e293b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-desc {
            font-size: 0.75rem;
            color: #64748b;
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

        /* Shortcut Grid */
        .shortcut-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .shortcut-card {
            background-color: #090d16;
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .shortcut-card:hover {
            border-color: rgba(56, 189, 248, 0.4);
            transform: scale(1.02);
        }

        .shortcut-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .shortcut-card:hover .shortcut-icon {
            transform: scale(1.1);
        }

        .shortcut-text {
            font-size: 0.8rem;
            font-weight: 600;
            color: #cbd5e1;
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
            border: 1px solid #334155;
            color: #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #1e293b;
            color: #ffffff;
            border-color: #475569;
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
            color: #cbd5e1;
            letter-spacing: 0.01em;
        }

        .form-input-custom {
            width: 100%;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            color: #ffffff;
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .form-input-custom:focus {
            border-color: #3b82f6;
            background-color: #1e293b;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-input-custom::placeholder {
            color: #64748b;
        }

        .form-select-custom {
            width: 100%;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            color: #ffffff;
            outline: none;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .form-select-custom:focus {
            border-color: #3b82f6;
            background-color: #1e293b;
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
            border-top: 1px solid #1e293b;
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

        .filter-input-search-wrapper {
            position: relative;
        }

        .filter-input-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .filter-input-search {
            padding-left: 36px !important;
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
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #1e293b;
            background-color: rgba(15, 23, 42, 0.4);
        }

        .table-custom td {
            padding: 14px 20px;
            font-size: 0.85rem;
            color: #cbd5e1;
            border-bottom: 1px solid rgba(30, 41, 59, 0.5);
            vertical-align: middle;
        }

        .table-custom tr:hover {
            background-color: rgba(30, 41, 59, 0.25);
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

        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.2);
            color: #22d3ee;
        }

        .badge-green {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
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
            .shortcut-grid {
                grid-template-columns: repeat(2, 1fr);
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

        /* Target active span inner element & other page anchors */
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

        /* Active page highlight */
        nav[role="navigation"] span[aria-current="page"] > span {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            cursor: default;
        }

        /* Disabled arrow links */
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
        });
    </script>
</head>
<body>

    <div class="admin-container">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <!-- Sidebar Brand/Logo -->
            <div class="sidebar-header">
                <h1 class="sidebar-title">Sistem IKU</h1>
                <span class="sidebar-subtitle">Program Studi</span>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <!-- MASTER DATA -->
                <div class="nav-category">
                    <span class="category-label">MASTER DATA</span>
                    <a href="{{ route('adminp2mp.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('adminp2mp.prodi.index') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.prodi.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kelola Program Studi
                    </a>

                    <a href="{{ route('adminp2mp.users.index') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.users.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Kelola User
                    </a>

                    <a href="{{ route('adminprodi.kategori.index') }}" 
                       class="nav-link {{ request()->routeIs('adminprodi.kategori.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Kategori IKU
                    </a>

                    <a href="{{ route('adminprodi.iku.index') }}" 
                       class="nav-link {{ request()->routeIs('adminprodi.iku.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Data IKU
                    </a>

                    <a href="{{ route('adminprodi.bukti.index') }}" 
                       class="nav-link {{ request()->routeIs('adminprodi.bukti.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Jenis Bukti
                    </a>
                </div>

                <!-- PENUGASAN -->
                <div class="nav-category">
                    <span class="category-label">PENUGASAN</span>
                    <a href="{{ route('adminp2mp.validasi') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.validasi') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Validasi Bukti IKU
                    </a>
                </div>

                <!-- LAPORAN & CONFIG -->
                <div class="nav-category">
                    <span class="category-label">LAPORAN & CONFIG</span>
                    <a href="{{ route('adminp2mp.monitoring') }}" 
                       class="nav-link {{ request()->routeIs('adminp2mp.monitoring') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Monitor & Laporan
                    </a>


                    <a href="{{ route('adminprodi.pengaturan.index') }}" 
                       class="nav-link {{ request()->routeIs('adminprodi.pengaturan.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan System
                    </a>
                </div>
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

                <!-- Profile Info Actions -->
                <a href="{{ route('profile') }}" class="user-profile-panel">
                    <div class="user-info">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                    </div>
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                </a>
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

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>

