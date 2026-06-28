@extends('adminp2mp.layouts.app')

@section('title', 'Dashboard Admin P2MP - Sistem Early Warning IKU')
@section('page_title', 'Selamat Datang di Sistem Monitoring Pencapaian Indikator Kinerja Politeknik Sukabumi')
@section('page_subtitle', '')

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
    <!-- Total Indikator IKU -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Total Indikator IKU</span>
                    <h4 class="stat-value" style="color: #6366f1;">{{ $totalIku }}</h4>
                </div>
                <div class="stat-icon target" style="background-color: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); color: #6366f1; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Seluruh Indikator IKU</span>
            <a href="{{ route('adminprodi.iku.index') }}" class="stat-link">
                Data IKU
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Validasi Bukti IKU Card -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Validasi Bukti IKU</span>
                    <h4 class="stat-value" style="color: {{ $pendingValidationCount > 0 ? '#fbbf24' : '#10b981' }};">{{ $pendingValidationCount }}</h4>
                </div>
                <div class="stat-icon validasi">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc" style="color: {{ $pendingValidationCount > 0 ? '#fbbf24' : '#64748b' }};">
                {{ $pendingValidationCount > 0 ? 'Perlu tindakan validasi' : 'Semua bukti divalidasi' }}
            </span>
            <a href="{{ route('adminp2mp.validasi') }}" class="stat-link" style="color: #10b981;">
                Validasi
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
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
                <div class="stat-icon tercapai" style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Target terpenuhi</span>
            <span class="badge-custom badge-blue">
                {{ $totalReports > 0 ? round(($achievedCount / $totalReports) * 100) : 0 }}%
            </span>
        </div>
    </div>

    <!-- Belum Tercapai -->
    <div class="stat-card">
        <div>
            <div class="stat-header">
                <div class="stat-info">
                    <span class="stat-label">Belum Tercapai</span>
                    <h4 class="stat-value" style="color: #ef4444;">{{ $unachievedCount }}</h4>
                </div>
                <div class="stat-icon belum-tercapai" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-footer">
            <span class="stat-desc">Belum memenuhi target</span>
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
                        <td style="text-align: center; font-weight: 700; color: {{ $persentase >= 80 ? '#10b981' : ($persentase >= 60 ? '#fbbf24' : '#ef4444') }};">
                            {{ $persentase }}%
                        </td>
                        <td style="text-align: right;">
                            @if($item->status === 'Tercapai')
                                <span class="badge-custom badge-green">Tercapai</span>
                            @elseif($item->status === 'Belum Tercapai')
                                <span class="badge-custom badge-yellow" style="background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); color: #fbbf24;">Belum Tercapai</span>
                            @else
                                <span class="badge-custom badge-rose">Berisiko Tidak Tercapai</span>
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

