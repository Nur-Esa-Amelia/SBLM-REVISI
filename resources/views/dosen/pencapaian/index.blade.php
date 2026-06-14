@extends('dosen.layouts.app')

@section('title', 'Target & Capaian IKU - Dosen')
@section('page_title', 'Target & Capaian IKU')
@section('page_subtitle', 'Target pemenuhan IKU program studi ' . $prodiName . ' dan status bukti yang Anda unggah')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Filter Year Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff;">Pilih Tahun Akademik</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Tampilkan target IKU prodi dan bukti capaian Anda pada tahun akademik terpilih.</p>
            </div>
            
            <form action="{{ route('dosen.pencapaian.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; width: 220px;">
                <div class="filter-item-custom" style="width: 100%;">
                    <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                        @foreach($tahunList as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="min-width: 200px;">Indikator Kinerja Utama</th>
                        <th style="text-align: center; width: 100px;">Target</th>
                        <th style="text-align: center; width: 120px;">Realisasi (Prodi)</th>
                        <th style="text-align: center; width: 130px;">Ketercapaian</th>
                        <th style="min-width: 250px;">Bukti Saya & Status Tugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pencapaianList as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            
                            <!-- Indikator IKU -->
                            <td>
                                <div style="font-weight: 600; color: #ffffff; line-height: 1.4;">{{ $item->iku->nama_iku }}</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                            </td>

                            <!-- Target -->
                            <td style="text-align: center; font-weight: 600; color: #cbd5e1;">
                                {{ $item->target }}{{ $item->satuan === 'persen' ? '%' : '' }}
                                <span style="font-size: 0.65rem; color: #64748b; display: block; font-weight: normal; margin-top: 2px;">({{ $item->objek }})</span>
                            </td>

                            <!-- Realisasi -->
                            <td style="text-align: center; font-weight: 700; color: #3b82f6;">
                                {{ round($item->realisasi) }} Bukti
                            </td>

                            <!-- Ketercapaian -->
                            <td style="text-align: center;">
                                @if($item->status === 'Tercapai')
                                    <span class="badge-custom badge-green">Tercapai</span>
                                @else
                                    <span class="badge-custom badge-rose">Belum Tercapai</span>
                                @endif
                            </td>

                            <!-- Bukti Saya & Status Tugas -->
                            <td>
                                @php
                                    $isAssigned = in_array($item->id_iku, $assignedIkuIds);
                                @endphp

                                @if($item->my_proofs->isNotEmpty())
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        @foreach($item->my_proofs as $proof)
                                            <div style="padding: 8px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 6px; display: flex; flex-direction: column; gap: 4px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                                                    <span style="font-size: 0.75rem; font-weight: 600; color: #94a3b8;">{{ $proof->buktiIku->nama_bukti }}</span>
                                                    
                                                    @if($proof->status === 'valid')
                                                        <span class="badge-custom badge-green" style="font-size: 0.6rem; padding: 1px 4px;">Valid</span>
                                                    @elseif($proof->status === 'invalid')
                                                        <span class="badge-custom badge-rose" style="font-size: 0.6rem; padding: 1px 4px;">Perlu Perbaikan</span>
                                                    @else
                                                        <span class="badge-custom badge-yellow" style="font-size: 0.6rem; padding: 1px 4px;">Awaiting</span>
                                                    @endif
                                                </div>

                                                <!-- Lampiran Files -->
                                                <details style="margin-top: 6px;">
                                                    <summary style="font-size: 0.72rem; color: #38bdf8; cursor: pointer; user-select: none; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: underline; outline: none;">
                                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                                                        </svg>
                                                        Lihat Lampiran ({{ $proof->files->count() }})
                                                    </summary>
                                                    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px; padding: 10px; background-color: rgba(15, 23, 42, 0.6); border: 1px solid #1e293b; border-radius: 6px;">
                                                        @foreach($proof->files as $file)
                                                            <div style="display: flex; flex-direction: column; gap: 4px; border-bottom: 1px solid rgba(30, 41, 59, 0.8); padding-bottom: 6px; margin-bottom: 4px;">
                                                                <a href="{{ asset($file->file_bukti) }}" target="_blank" style="color: #10b981; text-decoration: underline; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px; word-break: break-all; font-weight: 500;">
                                                                    <svg style="width: 12px; height: 12px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    {{ $file->nama_file }}
                                                                </a>
                                                                @if($file->keterangan)
                                                                    <div style="font-size: 0.68rem; color: #94a3b8; padding-left: 16px; line-height: 1.4;">
                                                                        <strong>Keterangan:</strong> {!! preg_replace('~(https?://[^\s<]+)~i', '<a href="$1" target="_blank" style="color: #3b82f6; text-decoration: underline; word-break: break-all;">$1</a>', e($file->keterangan)) !!}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </details>

                                                @if($proof->catatan_validator)
                                                    <div style="font-size: 0.68rem; color: #fb7185; margin-top: 2px; padding: 4px; background-color: rgba(244,63,94,0.04); border-radius: 4px; border: 1px solid rgba(244,63,94,0.08);">
                                                        <strong>Catatan:</strong> {{ $proof->catatan_validator }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach

                                        @if($isAssigned)
                                            <a href="{{ route('dosen.pengisian.create', ['id_iku' => $item->id_iku]) }}" class="btn btn-primary" style="padding: 4px 8px; font-size: 0.7rem; border-radius: 6px; align-self: flex-start; justify-content: center; height: 26px;">
                                                + Tambah Bukti Baru
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    @if($isAssigned)
                                        <div style="display: flex; flex-direction: column; gap: 6px;">
                                            <span style="font-size: 0.75rem; color: #f59e0b; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                Ditugaskan ke Anda & Belum Diisi
                                            </span>
                                            <a href="{{ route('dosen.pengisian.create', ['id_iku' => $item->id_iku]) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.72rem; border-radius: 6px; align-self: flex-start; justify-content: center; height: 28px;">
                                                Unggah Bukti Sekarang
                                            </a>
                                        </div>
                                    @else
                                        <span style="font-size: 0.75rem; color: #64748b; font-style: italic;">Bukan Tugas Anda</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px; color: #64748b;">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada data target IKU prodi untuk tahun akademik {{ $tahun }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
