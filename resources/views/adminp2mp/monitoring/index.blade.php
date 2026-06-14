@extends('adminp2mp.layouts.app')

@section('title', 'Monitor & Laporan - Sistem Early Warning IKU')
@section('page_title', 'Monitor & Laporan')
@section('page_subtitle', 'Pantau rekapitulasi capaian Indikator Kinerja Utama dari seluruh Program Studi')

@section('content')
<style>
    /* Styling khusus cetak laporan */
    @media print {
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-size: 11pt !important;
        }
        .sidebar, .top-header, .btn, .filter-row-custom, form {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
        }
        .table-custom {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .table-custom th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
            font-weight: bold !important;
        }
        .table-custom td {
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
        }
        .badge-custom {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            font-weight: bold !important;
        }
        .badge-green {
            color: #15803d !important;
        }
        .badge-rose {
            color: #b91c1c !important;
        }
        .badge-purple {
            color: #6b21a8 !important;
        }
        .print-header {
            display: block !important;
            text-align: center !important;
            margin-bottom: 30px !important;
        }
        .print-title {
            font-size: 16pt !important;
            font-weight: 700 !important;
            margin-bottom: 5px !important;
        }
        .print-subtitle {
            font-size: 11pt !important;
            color: #475569 !important;
        }
    }

    .print-header {
        display: none;
    }
</style>

<!-- Printable Header -->
<div class="print-header">
    <div class="print-title">LAPORAN MONITORING CAPAIAN INDIKATOR KINERJA UTAMA (IKU)</div>
    <div class="print-subtitle">PROGRAM STUDI: {{ strtoupper($prodiName) }} - TAHUN AKADEMIK {{ $tahun }}</div>
    <hr style="border: 0; border-top: 2px solid #000; margin-top: 15px;">
</div>

<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
            <form action="{{ route('adminp2mp.monitoring') }}" method="GET" style="display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap; flex: 1;">
                <!-- Prodi Selector -->
                <div class="filter-item-custom" style="max-width: 260px; min-width: 200px;">
                    <label for="prodi_id" class="form-label-custom">Pilih Program Studi</label>
                    <select id="prodi_id" name="prodi_id" class="form-select-custom" onchange="this.form.submit()">
                        @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ $prodiId == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Selector -->
                <div class="filter-item-custom" style="max-width: 200px; min-width: 150px;">
                    <label for="tahun" class="form-label-custom">Pilih Tahun Akademik</label>
                    <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                        @foreach($tahunList as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Print Button -->
            <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24-2.07A1.99 1.99 0 004.5 9.75h-.5m16 0h-.5a1.99 1.99 0 00-1.98 2.01l-.24 2.07M4 9.75V7.5a3 3 0 013-3h10a3 3 0 013 3v2.25m-14 0h14m-12 9h10a2 2 0 002-2v-3.75a2 2 0 00-2-2H7a2 2 0 00-2 2V17a2 2 0 002 2z"></path>
                </svg>
                Cetak Laporan (PDF)
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #1e293b;">
            <h3 class="text-base font-bold text-white" style="font-size: 0.95rem; margin-bottom: 2px;">Capaian IKU Program Studi {{ $prodiName }}</h3>
            <p style="font-size: 0.75rem; color: #64748b;">Daftar target, realisasi, dan status pencapaian IKU untuk tahun akademik {{ $tahun }}.</p>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Kategori IKU</th>
                        <th>Nama Indikator IKU</th>
                        <th style="text-align: center;">Target Sasaran</th>
                        <th style="text-align: center;">Realisasi (Valid)</th>
                        <th style="text-align: center;">Capaian (%)</th>
                        <th style="text-align: center; width: 140px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $index => $item)
                        @php
                            $targetVal = floatval($item->target);
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
                                <span class="badge-custom badge-purple">{{ $item->iku->kategori->nama_kategori }}</span>
                            </td>
                            <td style="font-weight: 600; color: #ffffff;">{{ $item->iku->nama_iku }}</td>
                            <td style="text-align: center; font-weight: 700; color: #cbd5e1;">
                                {{ $item->target }}{{ $item->satuan === 'persen' ? '%' : '' }}
                                <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: normal;">({{ $item->objek }})</span>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: #3b82f6;">
                                {{ round($item->realisasi) }} Bukti
                            </td>
                            <td style="text-align: center; font-weight: 700; color: {{ $persentase >= 100 ? '#10b981' : '#f59e0b' }};">
                                {{ $persentase }}%
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
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 40px;">
                                Belum ada target dan realisasi IKU yang tercatat untuk Program Studi {{ $prodiName }} pada tahun akademik {{ $tahun }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan Cetak (khusus print) -->
        <div class="print-header" style="margin-top: 50px;">
            <div style="display: flex; justify-content: space-between; padding: 0 50px;">
                <div style="text-align: center;">
                    <p>Mengetahui,</p>
                    <p style="font-weight: bold; margin-top: 50px;">Ketua Program Studi {{ $prodiName }}</p>
                    <p style="color: #64748b; font-size: 9pt;">(Tanda Tangan & Nama Terang)</p>
                </div>
                <div style="text-align: center;">
                    <p>Dibuat Oleh,</p>
                    <p style="font-weight: bold; margin-top: 50px;">Admin P2MP IKU</p>
                    <p style="color: #64748b; font-size: 9pt;">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

