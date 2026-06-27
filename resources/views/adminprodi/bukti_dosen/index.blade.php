@extends('adminprodi.layouts.app')

@section('title', 'Bukti IKU Dosen - Admin Prodi')
@section('page_title', 'Bukti IKU Dosen')
@section('page_subtitle', 'Melihat bukti pemenuhan IKU yang diunggah oleh dosen program studi ' . $prodiName)

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff;">Filter Bukti IKU</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Saring pengajuan bukti IKU berdasarkan status validasi atau tahun akademik.</p>
            </div>
        </div>

        <form action="{{ route('adminprodi.bukti-dosen') }}" method="GET" class="filter-row-custom" style="display: flex; gap: 16px; flex-wrap: wrap;">
            <!-- Status Filter -->
            <div class="filter-item-custom" style="flex: 1; min-width: 200px; max-width: 250px;">
                <label for="status" class="form-label-custom">Status Validasi</label>
                <select name="status" id="status" onchange="this.form.submit()" class="form-select-custom">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Awaiting Validasi</option>
                    <option value="valid" {{ $status === 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="invalid" {{ $status === 'invalid' ? 'selected' : '' }}>Perlu Perbaikan</option>
                </select>
            </div>

            <!-- Tahun Filter -->
            <div class="filter-item-custom" style="flex: 1; min-width: 200px; max-width: 250px;">
                <label for="tahun" class="form-label-custom">Tahun Akademik</label>
                <select name="tahun" id="tahun" onchange="this.form.submit()" class="form-select-custom">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            
            @if($status || request()->filled('tahun'))
                <div style="width: 100%; display: flex; justify-content: flex-end; margin-top: -8px;">
                    <a href="{{ route('adminprodi.bukti-dosen') }}" style="font-size: 0.75rem; font-weight: 600; color: #f43f5e; text-decoration: none;">
                        Hapus Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="min-width: 160px;">Dosen Penerima Tugas</th>
                        <th style="min-width: 180px;">Indikator IKU</th>
                        <th style="min-width: 140px;">Jenis Bukti</th>
                        <th style="min-width: 150px;">Berkas Lampiran</th>
                        <th>Keterangan Dosen</th>
                        <th style="text-align: center; width: 80px;">Tahun</th>
                        <th style="text-align: center; min-width: 150px;">Status Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $index => $item)
                        <tr>
                            <td>{{ $riwayat->firstItem() + $index }}</td>
                            
                            <!-- Dosen -->
                            <td>
                                <div style="font-weight: 700; color: #ffffff;">{{ $item->user->name }}</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">
                                    Email: {{ $item->user->email }}
                                </div>
                            </td>

                            <!-- Indikator IKU -->
                            <td>
                                <div style="font-weight: 600; color: #ffffff;">{{ $item->iku->nama_iku }}</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                            </td>

                            <!-- Jenis Bukti -->
                            <td>
                                <span style="font-weight: 500; color: #cbd5e1;">{{ $item->buktiIku->nama_bukti }}</span>
                            </td>

                            <!-- Berkas Lampiran -->
                            <td>
                                @if($item->files->isNotEmpty())
                                    <details>
                                        <summary style="font-size: 0.8rem; color: #38bdf8; cursor: pointer; user-select: none; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: underline; outline: none;">
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                                            </svg>
                                            Detail Berkas ({{ $item->files->count() }})
                                        </summary>
                                        <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 8px; padding: 8px; background-color: rgba(15, 23, 42, 0.6); border: 1px solid #1e293b; border-radius: 6px; min-width: 180px;">
                                            @foreach($item->files as $file)
                                                <a href="{{ asset($file->file_bukti) }}" target="_blank" style="color: #10b981; text-decoration: underline; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px; word-break: break-all;">
                                                    <svg style="width: 12px; height: 12px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $file->nama_file }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <span style="color: #64748b; font-size: 0.75rem;">Tidak ada berkas</span>
                                @endif
                            </td>

                            <!-- Keterangan -->
                            <td style="color: #cbd5e1; font-size: 0.8rem; max-width: 250px; white-space: normal; word-wrap: break-word;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    @forelse($item->files as $file)
                                        <div style="line-height: 1.4;">
                                            {!! preg_replace('~(https?://[^\s<]+)~i', '<a href="$1" target="_blank" style="color: #10b981; text-decoration: underline; word-break: break-all;">$1</a>', e($file->keterangan ?? '-')) !!}
                                        </div>
                                    @empty
                                        <span style="color: #64748b;">-</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Tahun -->
                            <td style="text-align: center; color: #cbd5e1; font-size: 0.85rem;">
                                {{ $item->tahun }}
                            </td>

                            <!-- Status -->
                            <td style="text-align: center;">
                                @if($item->status === 'valid')
                                    <span class="badge-custom badge-green">Valid</span>
                                @elseif($item->status === 'invalid')
                                    <span class="badge-custom badge-rose">Perlu Perbaikan</span>
                                @else
                                    <span class="badge-custom badge-yellow">Awaiting Validasi</span>
                                @endif
                                
                                @if($item->catatan_validator)
                                    <div style="font-size: 0.72rem; color: #fb7185; text-align: left; margin-top: 6px; padding: 6px; background-color: rgba(244,63,94,0.06); border-radius: 4px; border: 1px solid rgba(244,63,94,0.1); max-width: 200px; margin-left: auto; margin-right: auto; line-height: 1.3;">
                                        <strong>Catatan Validator:</strong> {{ $item->catatan_validator }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px; color: #64748b;">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada pengisian bukti IKU dari dosen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #1e293b; background-color: rgba(15, 23, 42, 0.2);">
                {{ $riwayat->appends(['status' => $status, 'tahun' => $tahun])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
