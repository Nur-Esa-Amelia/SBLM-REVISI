<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dosen - Sistem Early Warning IKU')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
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
            flex-direction: column;
            width: 100vw;
            min-height: 100vh;
        }

        /* Top Navbar Styles */
        .top-navbar {
            height: 70px;
            background-color: #0f172a;
            border-bottom: 1px solid #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(8px);
            flex-shrink: 0;
        }

        .navbar-brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: #10b981;
            font-weight: 600;
        }

        /* Horizontal Nav Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
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
            background-color: #10b981 !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Navbar Actions Area */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
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

        /* Main Workspace Layout */
        .main-body {
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 30px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 24px;
            flex: 1;
            overflow-y: auto;
        }

        /* Profile Dropdown Styles */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background-color 0.2s;
            outline: none;
            text-align: left;
        }

        .profile-dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }

        .profile-dropdown-toggle .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .profile-dropdown-toggle .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
        }

        .profile-dropdown-toggle .user-avatar {
            width: 38px;
            height: 38px;
            background-color: #1e293b;
            border: 2px solid #10b981;
            color: #10b981;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            width: 180px;
            background-color: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 8px;
            padding: 6px;
            display: none;
            flex-direction: column;
            gap: 4px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            z-index: 60;
        }

        .profile-dropdown.open .profile-dropdown-menu {
            display: flex;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 10px 12px;
            font-size: 0.825rem;
            font-weight: 500;
            color: #cbd5e1;
            background: transparent;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: left;
            text-decoration: none;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .profile-dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.04);
            color: #ffffff;
        }

        .profile-dropdown-item.text-rose {
            color: #fb7185;
        }

        .profile-dropdown-item.text-rose:hover {
            background-color: rgba(244, 63, 94, 0.08);
            color: #fb7185;
        }

        /* Page Header Title Styling */
        .page-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(30, 41, 59, 0.4) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: 4px solid #10b981;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
        }

        .page-title-text {
            font-size: 1.65rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            line-height: 1.3;
            letter-spacing: -0.02em;
        }

        .page-subtitle-text {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 4px;
            display: block;
        }

        /* Body container */
        .main-body {
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 30px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 24px;
            flex: 1;
            overflow-y: auto;
        }

        /* Cards and Components */
        .card {
            background: linear-gradient(145deg, #0f172a 0%, #111a2e 100%);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
        }

        .welcome-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.05) 50%, rgba(15, 23, 42, 0) 100%), #0f172a;
            border: 1px solid rgba(16, 185, 129, 0.15) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.05);
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
            color: #34d399;
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
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
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
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
            background: linear-gradient(145deg, #0f172a 0%, #131d31 100%);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            border-color: rgba(16, 185, 129, 0.25);
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4), 0 0 15px rgba(16, 185, 129, 0.05);
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

        .stat-icon.assign {
            background-color: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }

        .stat-icon.upload {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .stat-icon.valid {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .stat-icon.pending {
            background-color: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #f59e0b;
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
            color: #10b981;
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
            background-color: #10b981;
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .btn-primary:hover {
            background-color: #059669;
            border-color: #059669;
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
            cursor: pointer;
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
            border-color: #10b981;
            background-color: #1e293b;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
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
            border-color: #10b981;
            background-color: #1e293b;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
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

        .badge-yellow {
            background-color: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .badge-gray {
            background-color: rgba(100, 116, 139, 0.1);
            border-color: rgba(100, 116, 139, 0.2);
            color: #94a3b8;
        }

        /* Responsive rules */
        @media (max-width: 1024px) {
            .top-navbar {
                padding: 0 20px;
            }
            .nav-menu {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background-color: #0f172a;
                border-bottom: 1px solid #1e293b;
                flex-direction: column;
                align-items: stretch;
                padding: 16px;
                gap: 8px;
                z-index: 45;
            }
            .nav-menu.open {
                display: flex;
            }
            .menu-toggle-btn {
                display: block !important;
            }
            .navbar-actions {
                gap: 8px;
            }
            .user-info {
                display: none;
            }
            .main-body {
                padding: 20px;
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
            background-color: #10b981 !important;
            border-color: #10b981 !important;
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
            const navMenu = document.getElementById('nav-menu');

            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    navMenu.classList.toggle('open');
                });
            }

            // Profile dropdown toggle
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileToggle = document.querySelector('.profile-dropdown-toggle');

            if (profileDropdown && profileToggle) {
                profileToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('open');
                });
            }

            // Close dropdowns on click outside
            document.addEventListener('click', (e) => {
                if (profileDropdown) {
                    profileDropdown.classList.remove('open');
                }
                if (navMenu && mobileMenuBtn && !mobileMenuBtn.contains(e.target) && !navMenu.contains(e.target)) {
                    navMenu.classList.remove('open');
                }
            });
        });
    </script>
</head>
<body>

    <div class="admin-container">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div style="display: flex; align-items: center; gap: 20px;">
                <!-- Mobile Menu Hamburger Button -->
                <button id="mobile-menu-btn" class="menu-toggle-btn">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Navbar Brand -->
                <div class="navbar-brand">
                    <span class="brand-title">Sistem IKU</span>
                    <span class="brand-subtitle">{{ auth()->user()->prodi ? auth()->user()->prodi->nama_prodi : 'Program Studi' }}</span>
                </div>
            </div>

            <!-- Horizontal Nav Menu -->
            <nav id="nav-menu" class="nav-menu">
                <!-- Dashboard -->
                <a href="{{ route('dosen.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Target & Capaian IKU -->
                <a href="{{ route('dosen.pencapaian.index') }}" 
                   class="nav-link {{ request()->routeIs('dosen.pencapaian.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Target & Capaian IKU
                </a>

                <!-- Unggah Bukti IKU -->
                <a href="{{ route('dosen.pengisian.create') }}" 
                   class="nav-link {{ request()->routeIs('dosen.pengisian.create') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Unggah Bukti IKU
                </a>

                <!-- Riwayat Pengisian -->
                <a href="{{ route('dosen.pengisian.index') }}" 
                   class="nav-link {{ request()->routeIs('dosen.pengisian.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Riwayat Pengisian
                </a>
            </nav>

            <!-- Navbar Actions Area -->
            <div class="navbar-actions">
                <div class="profile-dropdown">
                    <button type="button" class="profile-dropdown-toggle">
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    </button>
                    <div class="profile-dropdown-menu">
                        <a href="{{ route('profile') }}" class="profile-dropdown-item">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Lihat Profil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
                            @csrf
                            <button type="submit" class="profile-dropdown-item text-rose">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                                </svg>
                                Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="main-body">
            @hasSection('page_title')
            <div class="page-header-container">
                <div class="page-title-group">
                    <h2 class="page-title-text">@yield('page_title')</h2>
                    @hasSection('page_subtitle')
                        @if(trim($__env->yieldContent('page_subtitle')) !== '')
                            <span class="page-subtitle-text">@yield('page_subtitle')</span>
                        @endif
                    @endif
                </div>
            </div>
            @endif

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

            @if($errors->any() && !request()->routeIs('*.store'))
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

</body>
</html>
