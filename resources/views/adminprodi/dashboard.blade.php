@extends('adminprodi.layouts.app')

@section('title', 'Dashboard Admin Prodi - Sistem Early Warning IKU')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Selamat datang di portal monitoring IKU program studi Anda')

@section('content')
<!-- Check configuration warning -->
@if(!$settings || $settings->jml_mahasiswa == 0 || $settings->jml_dosen == 0)
<div class="alert-box alert-danger" style="margin-bottom: 20px;">
    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
        <strong>Perhatian!</strong> Pengaturan jumlah mahasiswa atau dosen prodi Anda belum diset atau masih bernilai 0. Silakan isi terlebih dahulu di menu 
        <a href="{{ route('adminprodi.pengaturan.index') }}" style="color: inherit; text-decoration: underline; font-weight: bold;">Pengaturan System</a> 
        agar perhitungan target persentase berjalan dengan akurat.
    </div>
</div>
@endif

<!-- Welcome Announcement Card -->
<div class="card welcome-card">
    <div class="welcome-text">
        <span class="welcome-badge">Program Studi {{ $prodiName }}</span>
        <h3 class="welcome-title">Halo, {{ auth()->user()->name }}!</h3>
        <p class="welcome-desc">
            Anda login sebagai <strong>{{ auth()->user()->role === 'kaprodi' ? 'Ketua Program Studi (Kaprodi)' : 'Admin Program Studi' }}</strong>. Gunakan panel ini untuk mengelola konfigurasi prodi, kategori, target IKU tahunan, menugaskan dosen pengisi bukti, serta memantau laporan capaian IKU prodi secara real-time.
        </p>
    </div>
    <div style="display: flex; flex-direction: column; gap: 12px; flex-shrink: 0; min-width: 280px; max-width: 480px; width: 100%;">
        <!-- Tahun Akademik Aktif -->
        <div class="system-time-card" style="align-self: center; box-sizing: border-box; margin: 0; min-width: 220px;">
            <div class="time-icon">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="time-text">
                <span class="time-label">Tahun Akademik Aktif</span>
                <span class="time-value" style="color: #3b82f6;">{{ $tahunAktif }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 12px; width: 100%;">
            <!-- Jumlah Mahasiswa -->
            <div class="system-time-card" style="flex: 1; box-sizing: border-box; margin: 0; border-color: rgba(16, 185, 129, 0.2); padding: 10px 14px;">
                <div class="time-icon" style="background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10b981; width: 36px; height: 36px;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="time-text">
                    <span class="time-label" style="font-size: 0.6rem;">Jumlah Mahasiswa</span>
                    <span class="time-value" style="color: #10b981; font-size: 0.8rem;">{{ $settings?->jml_mahasiswa ?? 0 }} Mhs</span>
                </div>
            </div>

            <!-- Jumlah Dosen -->
            <div class="system-time-card" style="flex: 1; box-sizing: border-box; margin: 0; border-color: rgba(245, 158, 11, 0.2); padding: 10px 14px;">
                <div class="time-icon" style="background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); color: #f59e0b; width: 36px; height: 36px;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="time-text">
                    <span class="time-label" style="font-size: 0.6rem;">Jumlah Dosen</span>
                    <span class="time-value" style="color: #f59e0b; font-size: 0.8rem;">{{ $settings?->jml_dosen ?? 0 }} Dosen</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Metrics Cards Section -->
<div class="dashboard-grid">
    <!-- Total Target IKU -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Total Target IKU</span>
                    <h4 class="stat-value">{{ $totalTargets }}</h4>
                </div>
                <div class="stat-icon target">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Target diatur tahun {{ $tahunAktif }}</span>
            <a href="{{ route('adminprodi.pencapaian.index') }}" class="stat-link">
                Detail
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Total Bukti Terbaca (Valid) -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Realisasi (Bukti Valid)</span>
                    <h4 class="stat-value">{{ $totalValidProofs }}</h4>
                </div>
                <div class="stat-icon realisasi">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Telah divalidasi P2MP</span>
            <span class="badge-custom badge-green" style="font-size: 0.6rem;">Valid</span>
        </div>
    </div>

    <!-- IKU Tercapai -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">IKU Tercapai</span>
                    <h4 class="stat-value" style="color: #10b981;">{{ $achievedCount }}</h4>
                </div>
                <div class="stat-icon tercapai">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Target terpenuhi</span>
            <span class="badge-custom badge-blue">
                {{ $totalTargets > 0 ? round(($achievedCount / $totalTargets) * 100) : 0 }}%
            </span>
        </div>
    </div>

    <!-- IKU Belum Tercapai -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Belum Tercapai</span>
                    <h4 class="stat-value" style="color: #ef4444;">{{ $unachievedCount }}</h4>
                </div>
                <div class="stat-icon belum-tercapai">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Perlu didorong pengisian</span>
            <span class="badge-custom badge-rose">Warning</span>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- IKU Capaian Table -->
    <div class="card" style="display: flex; flex-direction: column; gap: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; font-weight: 700;">Monitoring Capaian IKU ({{ $tahunAktif }})</h3>
            <a href="{{ route('adminprodi.laporan.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; border-radius: 8px;">Lihat Laporan Lengkap</a>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Indikator Kinerja Utama</th>
                        <th style="text-align: center;">Target</th>
                        <th style="text-align: center;">Realisasi (Valid)</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pencapaians as $item)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #ffffff;">{{ $item->iku->nama_iku }}</div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                            </td>
                            <td style="text-align: center; font-weight: 600;">
                                {{ $item->target }}{{ $item->satuan === 'persen' ? '%' : '' }} 
                                <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: normal;">({{ $item->objek }})</span>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: #3b82f6;">
                                {{ round($item->realisasi) }} Bukti
                            </td>
                            <td style="text-align: center;">
                                @if($item->status === 'Tercapai')
                                    <span class="badge-custom badge-green">Tercapai</span>
                                @else
                                    <span class="badge-custom badge-rose">Belum Tercapai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b; padding: 30px;">
                                Belum ada target IKU yang diatur untuk tahun akademik aktif ({{ $tahunAktif }}). Silakan tambahkan target di menu Target IKU Tahunan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Assignments -->
    <div class="card" style="display: flex; flex-direction: column; gap: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; font-weight: 700;">Penugasan Dosen Terbaru</h3>
            <a href="{{ route('adminprodi.penugasan.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; border-radius: 8px;">Kelola</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            @forelse($recentAssignments as $assign)
                <div style="padding: 12px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 8px; display: flex; flex-direction: column; gap: 4px;">
                    <div style="font-size: 0.85rem; font-weight: 600; color: #ffffff;">{{ $assign->user->name }}</div>
                    <div style="font-size: 0.75rem; color: #cbd5e1;">IKU: {{ $assign->iku->nama_iku }}</div>
                    <div style="font-size: 0.7rem; color: #64748b; align-self: flex-end; margin-top: 4px;">Tahun: {{ $assign->tahun }}</div>
                </div>
            @empty
                <div style="text-align: center; color: #64748b; padding: 20px; font-size: 0.85rem;">
                    Belum ada penugasan dosen untuk tahun {{ $tahunAktif }}.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
