@extends('layouts.auth')

@section('title', 'Sistem Monitoring IKU/IKT')

@section('content')
<style>
    .welcome-container {
        width: 100%;
        max-width: 480px;
        background: linear-gradient(180deg, #7283B9 0%, #0080FF 100%);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 24px;
        padding: 48px 36px;
        text-align: center;
        z-index: 10;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.18), 0 10px 10px -5px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
        position: relative;
    }

    .logo-wrapper {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 110px;
        height: 110px;
        margin: 0 auto 24px;
        background-color: #ffffff;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .logo-wrapper img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        display: block;
    }

    .badge {
        display: inline-block;
        background-color: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #ffffff;
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
        color: rgba(255, 255, 255, 0.9);
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

    /* Register button: outline blue style */
    .btn-register {
        background-color: transparent;
        border: 1px solid #ffffff;
        color: #ffffff;
    }

    .btn-register:hover {
        background-color: rgba(255,255,255,0.16);
        color: #ffffff;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    /* Login button: filled white style */
    .btn-login {
        background-color: #f3f4f6;
        border: 1px solid #f3f4f6;
        color: #1f2937;
    }

    .btn-login:hover {
        background-color: rgba(255,255,255,0.9);
        color: #1f2937;
        border-color: rgba(255,255,255,0.9);
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
        color: rgba(255, 255, 255, 0.9);
        margin-top: 16px;
    }
</style>

<div class="welcome-container">
    <div class="logo-wrapper">
        <img src="{{ asset('images/LOGO POLTEKKKKK.jpg') }}" alt="Logo" style="height: 80px; width: auto; object-fit: contain;">
    </div>

    <div>
        <span class="badge">Politeknik Sukabumi</span>
    </div>

    <h1 class="title">Sistem Monitoring Pencapaian IKU/IKT</h1>
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
        <div style="margin-top: 16px; padding: 10px 16px; background-color: rgba(255, 255, 255, 0.14); border: 1px solid rgba(255, 255, 255, 0.22); border-radius: 12px; color: #ffffff; font-size: 0.8rem; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <div class="footer-text">
        Sistem Monitoring IKU/IKT — Politeknik Sukabumi
    </div>
</div>
@endsection
