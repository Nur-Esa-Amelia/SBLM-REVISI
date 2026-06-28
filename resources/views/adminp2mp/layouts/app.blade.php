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
            color: #64748b;
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
            background-color: #2563eb !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Top Dropdown Styles */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px 14px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            background: transparent;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            outline: none;
        }

        .nav-dropdown-toggle:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.04);
        }

        .nav-dropdown-toggle .toggle-label-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-label-wrapper svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-dropdown-toggle .chevron-icon {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
            color: #64748b;
        }

        .nav-dropdown.open .chevron-icon {
            transform: rotate(180deg);
            color: #ffffff;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 8px;
            width: 220px;
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

        .nav-dropdown.open .nav-dropdown-menu {
            display: flex;
        }

        .nav-dropdown-menu .nav-link {
            font-size: 0.8rem;
            padding: 8px 12px;
            width: 100%;
            box-sizing: border-box;
        }

        .nav-dropdown-menu .nav-link svg {
            width: 14px;
            height: 14px;
        }

        .nav-dropdown-menu .nav-link.active {
            background-color: rgba(37, 99, 235, 0.15) !important;
            border: 1px solid rgba(37, 99, 235, 0.3);
            box-shadow: none;
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
            border: 2px solid #2563eb;
            color: #3b82f6;
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

        /* Page Header Title Styling */
        .page-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(30, 41, 59, 0.4) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: 4px solid #2563eb;
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
            padding: 24px;
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
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(99, 102, 241, 0.05) 50%, rgba(15, 23, 42, 0) 100%), #0f172a;
            border: 1px solid rgba(37, 99, 235, 0.15) !important;
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
            .nav-dropdown-menu {
                position: static;
                width: 100%;
                margin-top: 4px;
                box-shadow: none;
                background-color: rgba(255, 255, 255, 0.02);
                border: none;
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
            .shortcut-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .welcome-card {
                flex-direction: column;
                align-items: stretch;
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
            const navMenu = document.getElementById('nav-menu');

            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    navMenu.classList.toggle('open');
                });
            }

            // Dropdown toggles
            const dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                const dropdown = toggle.closest('.nav-dropdown');
                
                // Toggle dropdown menu on click
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = dropdown.classList.contains('open');
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.nav-dropdown').forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('open');
                        }
                    });
                    
                    // Close profile dropdown
                    const profileDropdown = document.querySelector('.profile-dropdown');
                    if (profileDropdown) {
                        profileDropdown.classList.remove('open');
                    }

                    dropdown.classList.toggle('open');
                });
            });

            // Profile dropdown toggle
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileToggle = document.querySelector('.profile-dropdown-toggle');

            if (profileDropdown && profileToggle) {
                profileToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    
                    // Close all other nav dropdowns
                    document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                        dropdown.classList.remove('open');
                    });
                    
                    profileDropdown.classList.toggle('open');
                });
            }

            // Close dropdowns on click outside
            document.addEventListener('click', (e) => {
                document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
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
                    <span class="brand-subtitle">Program Studi</span>
                </div>
            </div>

            <!-- Horizontal Nav Menu -->
            <nav id="nav-menu" class="nav-menu">
                <!-- Dashboard -->
                <a href="{{ route('adminp2mp.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('adminp2mp.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Data Master Dropdown -->
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropdown-toggle">
                        <span class="toggle-label-wrapper">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                            <span>Data Master</span>
                        </span>
                        <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
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
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropdown-toggle">
                        <span class="toggle-label-wrapper">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Laporan & Pengaturan</span>
                        </span>
                        <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
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
                </div>
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

            @yield('content')
        </main>
    </div>

</body>
</html>

