@extends('layouts.auth')

@section('title', 'Dashboard - Sistem Early Warning IKU')

@section('content')
<div class="auth-card">
    <div class="text-center mb-6">
        <!-- Dashboard Welcome Badge -->
        <span class="badge" style="background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10b981; margin-bottom: 16px;">
            Sesi Aktif
        </span>

        <h1 class="auth-title">Dashboard</h1>
        <p class="auth-subtitle">Selamat datang di portal monitoring IKU Anda</p>
    </div>

    <!-- Success Message Alert -->
    @if(session('success'))
        <div style="margin-bottom: 24px; padding: 12px 16px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; color: #10b981; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg style="width: 16px; height: 16px; shrink-0: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Details Container -->
    <div style="background-color: #0f1626; border: 1px solid #242f47; border-radius: 16px; padding: 24px; mb-6: 24px; margin-bottom: 24px; display: flex; flex-direction: column; gap: 16px;">
        <!-- Name Row -->
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; uppercase: uppercase; tracking-wide: 0.05em; text-transform: uppercase;">Nama Lengkap</span>
            <span style="font-size: 0.95rem; font-weight: 600; color: #ffffff; margin-top: 4px;">{{ auth()->user()->name }}</span>
        </div>

        <hr style="border: 0; border-top: 1px solid #242f47;">

        <!-- Email Row -->
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; uppercase: uppercase; tracking-wide: 0.05em; text-transform: uppercase;">Alamat Email</span>
            <span style="font-size: 0.95rem; font-weight: 600; color: #cbd5e1; margin-top: 4px;">{{ auth()->user()->email }}</span>
        </div>

        <hr style="border: 0; border-top: 1px solid #242f47;">

        <!-- Program Studi Row -->
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; uppercase: uppercase; tracking-wide: 0.05em; text-transform: uppercase;">Program Studi</span>
            <span style="font-size: 0.95rem; font-weight: 600; color: #38bdf8; margin-top: 4px;">
                {{ auth()->user()->prodi?->nama_prodi ?? 'Tidak Terkait Prodi' }}
            </span>
        </div>

        <hr style="border: 0; border-top: 1px solid #242f47;">

        <!-- Role Row -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; uppercase: uppercase; tracking-wide: 0.05em; text-transform: uppercase;">Hak Akses / Role</span>
                <span style="font-size: 0.95rem; font-weight: 600; color: #cbd5e1; margin-top: 4px; text-transform: capitalize;">
                    {{ str_replace('_', ' ', auth()->user()->role) }}
                </span>
            </div>
            
            <span style="padding: 4px 10px; font-size: 0.7rem; font-weight: 700; background-color: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>

    <!-- Actions -->
    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
        @csrf
        <button type="submit" class="btn-action" style="background-color: transparent; border: 1px solid #f43f5e; color: #f43f5e; font-weight: 600; cursor: pointer; transition: all 0.2s ease;"
                onmouseover="this.style.backgroundColor='rgba(244, 63, 94, 0.08)'"
                onmouseout="this.style.backgroundColor='transparent'">
            <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Keluar dari Sistem
        </button>
    </form>
</div>
@endsection
