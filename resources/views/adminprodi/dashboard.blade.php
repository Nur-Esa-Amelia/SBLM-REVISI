@extends('adminprodi.layouts.app')

@section('title', 'Dashboard Admin Prodi - Sistem Early Warning IKU')
@section('page_title', 'Selamat Datang di Sistem Monitoring Pencapaian Indikator Kinerja Politeknik Sukabumi')
@section('page_subtitle', '')

@section('content')
<style>
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    .shimmer {
        background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    #ai-content::-webkit-scrollbar {
        width: 6px;
    }
    #ai-content::-webkit-scrollbar-track {
        background: #0f172a;
    }
    #ai-content::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 3px;
    }
    #ai-content::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }
</style>

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
            Anda login sebagai <strong>{{ auth()->user()->role === 'kaprodi' ? 'Ketua Program Studi (Kaprodi)' : 'Admin Program Studi' }}</strong>. 
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

<!-- Balanced Scorecard (BSC) Averages -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <!-- Perspektif Mahasiswa -->
    <div class="card" style="display: flex; align-items: center; gap: 20px; padding: 20px; position: relative; overflow: hidden; border-color: rgba(56, 189, 248, 0.15);">
        <div style="position: relative; width: 76px; height: 76px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: conic-gradient({{ $avgMahasiswa >= 80 ? '#10b981' : ($avgMahasiswa >= 60 ? '#f59e0b' : '#ef4444') }} {{ $avgMahasiswa * 3.6 }}deg, #1e293b 0deg); flex-shrink: 0; box-shadow: inset 0 0 8px rgba(0,0,0,0.5);">
            <div style="content: ''; position: absolute; width: 60px; height: 60px; border-radius: 50%; background-color: #0f172a;"></div>
            <span style="position: relative; font-size: 1.1rem; font-weight: 800; color: #ffffff;">{{ $avgMahasiswa }}%</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <h4 style="font-size: 0.85rem; font-weight: 700; color: #cbd5e1; margin: 0;">Perspektif Mahasiswa</h4>
            <span style="font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Balanced Scorecard</span>
            <span class="badge-custom {{ $avgMahasiswa >= 80 ? 'badge-green' : ($avgMahasiswa >= 60 ? 'badge-blue' : 'badge-rose') }}" style="align-self: flex-start; font-size: 0.6rem; margin-top: 2px;">
                {{ $avgMahasiswa >= 80 ? 'Sangat Baik' : ($avgMahasiswa >= 60 ? 'Cukup Baik' : 'Kurang/Risiko') }}
            </span>
        </div>
    </div>

    <!-- Perspektif Dosen -->
    <div class="card" style="display: flex; align-items: center; gap: 20px; padding: 20px; position: relative; overflow: hidden; border-color: rgba(168, 85, 247, 0.15);">
        <div style="position: relative; width: 76px; height: 76px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: conic-gradient({{ $avgDosen >= 80 ? '#10b981' : ($avgDosen >= 60 ? '#f59e0b' : '#ef4444') }} {{ $avgDosen * 3.6 }}deg, #1e293b 0deg); flex-shrink: 0; box-shadow: inset 0 0 8px rgba(0,0,0,0.5);">
            <div style="content: ''; position: absolute; width: 60px; height: 60px; border-radius: 50%; background-color: #0f172a;"></div>
            <span style="position: relative; font-size: 1.1rem; font-weight: 800; color: #ffffff;">{{ $avgDosen }}%</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <h4 style="font-size: 0.85rem; font-weight: 700; color: #cbd5e1; margin: 0;">Perspektif Dosen</h4>
            <span style="font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Balanced Scorecard</span>
            <span class="badge-custom {{ $avgDosen >= 80 ? 'badge-green' : ($avgDosen >= 60 ? 'badge-purple' : 'badge-rose') }}" style="align-self: flex-start; font-size: 0.6rem; margin-top: 2px;">
                {{ $avgDosen >= 80 ? 'Sangat Baik' : ($avgDosen >= 60 ? 'Cukup Baik' : 'Kurang/Risiko') }}
            </span>
        </div>
    </div>
</div>

    @if($recommendations && $recommendations->isNotEmpty())
        <div class="alert-box alert-warning" style="background: rgba(168, 85, 247, 0.1); border: 1px solid rgba(168, 85, 247, 0.3); color: #cbd5e1; display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
            <svg style="width: 18px; height: 18px; color: #c084fc; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.982-11.795H13.62l1.378-6.059L6 15.004h3.813z"></path>
            </svg>
            <div style="font-size: 0.85rem;">
                Terdeteksi <strong>{{ $recommendations->count() }}</strong> indikator dengan status warning. Klik tombol <strong>💡 Rekomendasi</strong> di kolom status tabel untuk melihat saran perbaikan AI.
            </div>
        </div>
    @else
        <div class="alert-box alert-success" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #94a3b8; display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
            <svg style="width: 18px; height: 18px; color: #10b981; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div style="font-size: 0.85rem;">
                Tidak ada rekomendasi karena seluruh indikator dalam kondisi aman.
            </div>
        </div>
    @endif

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
                            <td style="text-align: center; vertical-align: middle;">
                                @if($item->status === 'Tercapai')
                                    <span class="badge-custom badge-green">Tercapai</span>
                                @elseif($item->status === 'Belum Tercapai')
                                    <span class="badge-custom badge-yellow" style="background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); color: #fbbf24; display: block; margin-bottom: 6px;">Belum Tercapai</span>
                                    <button type="button" class="btn-show-ai-rec" data-pencapaian-id="{{ $item->id }}" style="padding: 3px 8px; font-size: 0.72rem; border-radius: 6px; background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3); color: #c084fc; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; outline: none;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.982-11.795H13.62l1.378-6.059L6 15.004h3.813z"></path>
                                        </svg>
                                        Rekomendasi
                                    </button>
                                @else
                                    <span class="badge-custom badge-rose" style="display: block; margin-bottom: 6px;">Berisiko Tidak Tercapai</span>
                                    <button type="button" class="btn-show-ai-rec" data-pencapaian-id="{{ $item->id }}" style="padding: 3px 8px; font-size: 0.72rem; border-radius: 6px; background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3); color: #c084fc; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; outline: none;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.982-11.795H13.62l1.378-6.059L6 15.004h3.813z"></path>
                                        </svg>
                                        Rekomendasi
                                    </button>
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

<!-- Custom AI Recommendation Modal -->
<div id="custom-ai-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px; transition: all 0.3s ease;">
    <div style="background: #0f172a; border: 1px solid rgba(168, 85, 247, 0.4); box-shadow: 0 0 30px rgba(168, 85, 247, 0.25); border-radius: 12px; width: 100%; max-width: 750px; max-height: 85vh; display: flex; flex-direction: column; animation: modalSlideIn 0.25s ease-out; overflow: hidden;">
        <!-- Modal Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1e293b; padding: 16px 20px; background: #0f172a;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(168, 85, 247, 0.15); display: flex; align-items: center; justify-content: center; color: #a855f7;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.982-11.795H13.62l1.378-6.059L6 15.004h3.813z"></path>
                    </svg>
                </div>
                <div>
                    <h3 id="modal-title" style="font-size: 0.95rem; font-weight: 700; color: #ffffff; margin: 0;">Rekomendasi Analisis AI</h3>
                    <p id="modal-subtitle" style="font-size: 0.75rem; color: #64748b; margin: 2px 0 0 0;"></p>
                </div>
            </div>
            <button id="btn-close-modal" style="background: transparent; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 6px; transition: all 0.2s;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Modal Body -->
        <div id="modal-body-content" style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6; padding: 20px; overflow-y: auto; flex: 1; max-height: calc(85vh - 75px);">
            <!-- Rendered markdown recommendation goes here -->
        </div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from {
        transform: scale(0.96) translateY(8px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preloaded recommendations keyed by id_iku_pencapaian
    const recommendationsData = {!! json_encode($recommendations->keyBy('id_iku_pencapaian')) !!};
    const modal = document.getElementById('custom-ai-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalSubtitle = document.getElementById('modal-subtitle');
    const modalBody = document.getElementById('modal-body-content');
    const btnCloseModal = document.getElementById('btn-close-modal');

    // Attach click events to all Rekomendasi buttons in table
    document.querySelectorAll('.btn-show-ai-rec').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const pencapaianId = btn.getAttribute('data-pencapaian-id');
            const data = recommendationsData[pencapaianId];
            if (data) {
                modalTitle.textContent = 'Rekomendasi Analisis AI: ' + (data.iku_pencapaian.iku ? data.iku_pencapaian.iku.nama_iku : 'IKU');
                modalSubtitle.innerHTML = 'Status: <span style="font-weight: 600; color: ' + 
                    (data.iku_pencapaian.status === 'Belum Tercapai' ? '#fbbf24' : '#ef4444') + ';">' + 
                    data.iku_pencapaian.status + '</span> (Realisasi: ' + Math.round(data.iku_pencapaian.realisasi) + ' dari Target: ' + data.iku_pencapaian.target + ')';
                
                modalBody.innerHTML = parseMarkdown(data.rekomendasi);
                modal.style.display = 'flex';
            }
        });
    });

    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    // Close on click outside modal content
    window.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // A lightweight helper to parse subset of markdown styles safely
    function parseMarkdown(text) {
        // Escape HTML
        let html = text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
            
        // Headers
        html = html.replace(/^### (.*$)/gim, '<h5 style="color: #f1f5f9; font-weight: 700; margin-top: 14px; margin-bottom: 6px; font-size: 0.9rem;">$1</h5>');
        html = html.replace(/^## (.*$)/gim, '<h4 style="color: #ffffff; font-weight: 700; margin-top: 18px; margin-bottom: 8px; font-size: 1rem; border-bottom: 1px solid #1e293b; padding-bottom: 4px;">$1</h4>');
        html = html.replace(/^# (.*$)/gim, '<h3 style="color: #ffffff; font-weight: 800; margin-top: 22px; margin-bottom: 10px; font-size: 1.15rem;">$1</h3>');
        
        // Bold
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong style="color: #ffffff; font-weight: 600;">$1</strong>');
        
        // Bullet Lists: match a line beginning with standard bullet characters
        html = html.replace(/^\s*[-*+]\s+(.*)$/gim, '<li style="margin-left: 20px; margin-bottom: 6px; list-style-type: disc; padding-left: 4px;">$1</li>');
        
        // Split by newlines and handle empty lines / wrap lists
        const lines = html.split('\n');
        let processedLines = [];
        let inList = false;

        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            if (line.startsWith('<li')) {
                if (!inList) {
                    processedLines.push('<ul style="margin-bottom: 12px; display: flex; flex-direction: column; gap: 4px;">');
                    inList = true;
                }
                processedLines.push(line);
            } else {
                if (inList) {
                    processedLines.push('</ul>');
                    inList = false;
                }
                if (line === '') {
                    // skip empty lines
                } else if (line.startsWith('<h')) {
                    processedLines.push(line);
                } else {
                    processedLines.push(`<p style="margin-bottom: 12px; text-align: justify; color: #cbd5e1;">${line}</p>`);
                }
            }
        }
        if (inList) {
            processedLines.push('</ul>');
        }

        return processedLines.join('\n');
    }
});
</script>
@endsection
