@extends('layouts.auth')

@section('title', 'Sistem Monitoring IKU')

@section('content')
<style>
    .welcome-container {
        width: 100%;
        max-width: 480px;
        background-color: #171d2c;
        border: 1px solid #242f47;
        border-radius: 24px;
        padding: 48px 36px;
        text-align: center;
        z-index: 10;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
    }

    .logo-wrapper {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 64px;
        height: 64px;
        background-color: #0f1626;
        border: 1px solid #1e293b;
        border-radius: 16px;
        margin-bottom: 24px;
        color: #38bdf8;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
    }

    .logo-wrapper svg {
        width: 32px;
        height: 32px;
    }

    .badge {
        display: inline-block;
        background-color: rgba(14, 165, 233, 0.1);
        border: 1px solid rgba(14, 165, 233, 0.2);
        color: #38bdf8;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 9999px;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 12px;
        color: #ffffff;
        line-height: 1.25;
    }

    .subtitle {
        font-size: 0.95rem;
        color: #94a3b8;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .actions-group {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 28px;
    }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        font-size: 1rem;
        font-weight: 600;
        padding: 14px 20px;
        border-radius: 12px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    /* Register button: bright outline blue style */
    .btn-register {
        background-color: transparent;
        border: 1px solid #38bdf8;
        color: #38bdf8;
    }

    .btn-register:hover {
        background-color: #38bdf8;
        color: #0b0f19;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
        transform: translateY(-2px);
    }

    /* Login button: filled white style */
    .btn-login {
        background-color: #f8fafc;
        border: 1px solid #f8fafc;
        color: #0b0f19;
    }

    .btn-login:hover {
        background-color: transparent;
        color: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .btn-action:active {
        transform: translateY(0);
    }

    .btn-action svg {
        width: 20px;
        height: 20px;
    }

    .footer-text {
        font-size: 0.8rem;
        color: #475569;
        margin-top: 16px;
    }
</style>

<div class="welcome-container">
    <div class="logo-wrapper">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>

    <div>
        <span class="badge">Sistem IKU</span>
    </div>

    <h1 class="title">Sistem Monitoring Pencapaian IKU</h1>
    <p class="subtitle">Aplikasi Monitoring Pencapaian Indikator Kinerja Utama</p>

    <div class="actions-group">
        <!-- <a href="{{ route('register') }}" class="btn-action btn-register">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Daftar Akun
        </a> -->
        <a href="{{ route('login') }}" class="btn-action btn-login">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Login ke Sistem
        </a>
    </div>

    @if(session('success'))
        <div style="margin-top: 16px; padding: 10px 16px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; color: #10b981; font-size: 0.8rem; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <div class="footer-text">
        Sistem Early Warning IKU — Program Studi
    </div>
</div>
@endsection
