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

            <div style="display: flex; gap: 10px; align-items: center;">
                <!-- Print Button -->
                <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24-2.07A1.99 1.99 0 004.5 9.75h-.5m16 0h-.5a1.99 1.99 0 00-1.98 2.01l-.24 2.07M4 9.75V7.5a3 3 0 013-3h10a3 3 0 013 3v2.25m-14 0h14m-12 9h10a2 2 0 002-2v-3.75a2 2 0 00-2-2H7a2 2 0 00-2 2V17a2 2 0 002 2z"></path>
                    </svg>
                    Cetak Laporan (PDF)
                </button>
            </div>
        </div>
    </div>

    @if($recommendations && $recommendations->isNotEmpty())
        <div class="alert-box alert-warning" style="background: rgba(168, 85, 247, 0.1); border: 1px solid rgba(168, 85, 247, 0.3); color: #cbd5e1; display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 8px; margin-bottom: 8px;">
            <svg style="width: 18px; height: 18px; color: #c084fc; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.982-11.795H13.62l1.378-6.059L6 15.004h3.813z"></path>
            </svg>
            <div style="font-size: 0.85rem;">
                Terdeteksi <strong>{{ $recommendations->count() }}</strong> indikator dengan status warning. Klik tombol <strong>💡 Rekomendasi</strong> di kolom status tabel untuk melihat saran perbaikan AI.
            </div>
        </div>
    @else
        <div class="alert-box alert-success" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #94a3b8; display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 8px; margin-bottom: 8px;">
            <svg style="width: 18px; height: 18px; color: #10b981; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div style="font-size: 0.85rem;">
                Tidak ada rekomendasi karena seluruh indikator dalam kondisi aman.
            </div>
        </div>
    @endif

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
                            <td style="text-align: center; font-weight: 700; color: {{ $persentase >= 80 ? '#10b981' : ($persentase >= 60 ? '#fbbf24' : '#ef4444') }};">
                                {{ $persentase }}%
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

