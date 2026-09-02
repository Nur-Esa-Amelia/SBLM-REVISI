@extends('adminsistem.layouts.app')

@section('title', 'Dashboard Admin Sistem')
@section('page_title', 'Selamat Datang di Sistem Monitoring Pencapaian Indikator Kinerja Politeknik Sukabumi')
@section('page_subtitle', 'Dashboard Admin Sistem')

@section('content')
<!-- Welcome Announcement Card -->
<div class="card welcome-card">
    <div class="welcome-text">
        <span class="welcome-badge">Selamat Datang</span>
        <h3 class="welcome-title">Halo, {{ auth()->user()->name }}!</h3>
        <p class="welcome-desc">
            Anda login sebagai <strong>Admin Sistem</strong>.
        </p>
    </div>
    <div class="system-time-card">
        <div class="time-icon time-icon-1">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div class="time-text">
            <span class="time-label">Waktu Sistem</span>
            <span class="time-value time-value-1">{{ date('d M Y') }}</span>
        </div>
    </div>
</div>

<!-- Metrics Cards Section -->
<div class="dashboard-grid">
    <!-- Total Program Studi -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Total Program Studi</span>
                    <h4 class="stat-value" style="color: #6366f1;">{{ $totalProdi }}</h4>
                </div>
                <div class="stat-icon prodi">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Program Studi Terdaftar</span>
            <a href="{{ route('adminsistem.prodi.index') }}" class="stat-link">
                Kelola Prodi
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Total User -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Total User</span>
                    <h4 class="stat-value" style="color: #10b981;">{{ $totalUsers }}</h4>
                </div>
                <div class="stat-icon user">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">User Sistem Terdaftar</span>
            <a href="{{ route('adminsistem.users.index') }}" class="stat-link">
                Kelola User
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- AI Model -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Model AI Aktif</span>
                    <h4 class="stat-value" style="color: #f59e0b; font-size: 1.2rem;">{{ strtoupper($aiModel) }}</h4>
                </div>
                <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Konfigurasi Model & Token</span>
            <a href="{{ route('adminsistem.model_ai.index') }}" class="stat-link">
                Kelola AI
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
