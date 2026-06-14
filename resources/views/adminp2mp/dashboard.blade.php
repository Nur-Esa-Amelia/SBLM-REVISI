@extends('adminp2mp.layouts.app')

@section('title', 'Dashboard Admin P2MP - Sistem Early Warning IKU')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Selamat datang di portal monitoring IKU Anda')

@section('content')
<!-- Welcome Announcement Card -->
<div class="card welcome-card">
    <div class="welcome-text">
        <span class="welcome-badge">Selamat Datang</span>
        <h3 class="welcome-title">Halo, {{ auth()->user()->name }}!</h3>
        <p class="welcome-desc">
            Anda login sebagai <strong>Admin P2MP</strong>. 
        </p>
    </div>
    <div class="system-time-card">
        <div class="time-icon">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div class="time-text">
            <span class="time-label">Waktu Sistem</span>
            <span class="time-value">{{ date('d M Y') }}</span>
        </div>
    </div>
</div>

<!-- Metrics Cards Section -->
<div class="dashboard-grid">
    <!-- Kelola User Card -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Total User</span>
                    <h4 class="stat-value">{{ $totalUsers }}</h4>
                </div>
                <div class="stat-icon user">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">User semua prodi</span>
            <a href="{{ route('adminp2mp.users.index') }}" class="stat-link">
                Kelola
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Kelola Prodi Card -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Total Program Studi</span>
                    <h4 class="stat-value">{{ $totalProdi }}</h4>
                </div>
                <div class="stat-icon prodi">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Program Studi aktif</span>
            <a href="{{ route('adminp2mp.prodi.index') }}" class="stat-link">
                Kelola
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Validasi Bukti IKU Card (Placeholder) -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Validasi Bukti IKU</span>
                    <h4 class="stat-value">{{ $pendingValidationCount }}</h4>
                </div>
                <div class="stat-icon validasi">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc" style="color: #f59e0b;">Belum ada pengajuan</span>
            <a href="{{ route('adminp2mp.validasi') }}" class="stat-link" style="color: #10b981;">
                Validasi
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Monitor & Laporan Card (Placeholder) -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Monitor & Laporan</span>
                    <h4 class="stat-value">-</h4>
                </div>
                <div class="stat-icon report">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc" style="color: #6366f1;">Tabel iku_capaian kosong</span>
            <a href="{{ route('adminp2mp.monitoring') }}" class="stat-link" style="color: #6366f1;">
                Pantau
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Table IKU Capaian -->
<div class="card" style="display: flex; flex-direction: column; gap: 16px; padding: 0; overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #1e293b; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="text-base font-bold text-white" style="font-size: 0.95rem; margin-bottom: 2px;">Capaian IKU Keseluruhan</h3>
            <p style="font-size: 0.75rem; color: #64748b;">Rekap capaian Indikator Kinerja Utama seluruh Program Studi untuk tahun {{ $tahun }}.</p>
        </div>
        
        <form action="{{ route('adminp2mp.dashboard') }}" method="GET" style="display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap;">
            <!-- Prodi Selector -->
            <div class="filter-item-custom" style="max-width: 260px; min-width: 200px;">
                <label for="prodi_id" class="form-label-custom" style="font-size: 0.75rem; font-weight: 600; color: #cbd5e1; margin-bottom: 4px;">Program Studi</label>
                <select id="prodi_id" name="prodi_id" class="form-select-custom" style="padding: 8px 12px; font-size: 0.8rem;" onchange="this.form.submit()">
                    <option value="">-- Semua Program Studi --</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ $prodiId == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Selector -->
            <div class="filter-item-custom" style="max-width: 200px; min-width: 150px;">
                <label for="tahun" class="form-label-custom" style="font-size: 0.75rem; font-weight: 600; color: #cbd5e1; margin-bottom: 4px;">Tahun Akademik</label>
                <select id="tahun" name="tahun" class="form-select-custom" style="padding: 8px 12px; font-size: 0.8rem;" onchange="this.form.submit()">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th>PROGRAM STUDI</th>
                    <th>IKU</th>
                    <th>KATEGORI</th>
                    <th style="text-align: center;">TARGET</th>
                    <th style="text-align: center;">REALISASI</th>
                    <th style="text-align: center;">% CAPAIAN</th>
                    <th style="text-align: right;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $index => $item)
                    @php
                        $targetVal = floatval($item->target);
                        $settings = $settingsMap->get($item->id_prodi);
                        $jml_mahasiswa = $settings ? $settings->jml_mahasiswa : 0;
                        $jml_dosen = $settings ? $settings->jml_dosen : 0;
                        
                        if ($item->satuan === 'persen') {
                            if ($item->objek === 'mahasiswa') {
                                $targetNyata = ($targetVal / 100) * $jml_mahasiswa;
                            } elseif ($item->objek === 'dosen') {
                                $targetNyata = ($targetVal / 100) * $jml_dosen;
                            } else {
                                $targetNyata = $targetVal;
                            }
                        } else {
                            $targetNyata = $targetVal;
                        }

                        if ($targetNyata > 0) {
                            $persentase = round(($item->realisasi / $targetNyata) * 100);
                        } else {
                            $persentase = $item->realisasi > 0 ? 100 : 0;
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span style="font-weight: 600; color: #38bdf8;">{{ $item->prodi->nama_prodi }}</span>
                        </td>
                        <td style="font-weight: 700; color: #ffffff;">{{ $item->iku->nama_iku }}</td>
                        <td>{{ $item->iku->kategori->nama_kategori }}</td>
                        <td style="text-align: center; font-weight: 600; color: #cbd5e1;">
                            {{ $item->target }}{{ $item->satuan === 'persen' ? '%' : '' }}
                            <span style="font-size: 0.65rem; color: #64748b; display: block; font-weight: normal;">({{ $item->objek }})</span>
                        </td>
                        <td style="text-align: center; font-weight: 700; color: #3b82f6;">
                            {{ round($item->realisasi) }} Bukti
                        </td>
                        <td style="text-align: center; font-weight: 700; color: {{ $persentase >= 100 ? '#10b981' : '#f59e0b' }};">
                            {{ $persentase }}%
                        </td>
                        <td style="text-align: right;">
                            @if($item->status === 'Tercapai')
                                <span class="badge-custom badge-green">Tercapai</span>
                            @else
                                <span class="badge-custom badge-rose">Belum Tercapai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #64748b; padding: 40px;">
                            Belum ada target dan realisasi IKU keseluruhan yang tercatat untuk tahun akademik {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

