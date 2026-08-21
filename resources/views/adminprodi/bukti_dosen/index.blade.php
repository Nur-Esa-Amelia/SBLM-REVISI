@extends('adminprodi.layouts.app')

@section('title', 'Bukti IKU/IKT Dosen - Admin Prodi')
@section('page_title', 'Bukti IKU/IKT Dosen')
@section('page_subtitle', 'Melihat bukti pemenuhan IKU/IKT yang diunggah oleh dosen program studi ' . $prodiName)

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">Filter Bukti IKU/IKT</h3>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Saring pengajuan bukti IKU/IKT berdasarkan status validasi atau tahun akademik.</p>
            </div>
            @if(auth()->user()->role === 'kaprodi')
                <a href="{{ route('adminprodi.pengisian.create') }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Unggah Bukti (Kaprodi)
                </a>
            @endif
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
                        <th style="min-width: 160px;">Pengunggah</th>
                        <th style="min-width: 180px;">Indikator IKU/IKT</th>
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
                            
                            <!-- Pengunggah -->
                            <td>
                                <div style="font-weight: 700; color: var(--text-primary);">{{ $item->user->name }}</div>
                                <div style="font-size: 0.72rem; color: #38bdf8; margin-top: 2px; font-weight: 600;">
                                    Role: {{ $item->user->role === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}
                                </div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">
                                    Email: {{ $item->user->email }}
                                </div>
                            </td>

                            <!-- Indikator IKU/IKT -->
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $item->iku->nama_iku }}</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                            </td>

                            <!-- Jenis Bukti -->
                            <td>
                                <span style="font-weight: 500; color: var(--text-secondary);">{{ $item->buktiIku->nama_bukti }}</span>
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
                                        <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 8px; padding: 8px; background-color: var(--bg-surface2); border: 1px solid var(--border); border-radius: 6px; min-width: 180px;">
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
                                    <span style="color: var(--text-muted); font-size: 0.75rem;">Tidak ada berkas</span>
                                @endif
                            </td>

                            <!-- Keterangan -->
                            <td style="color: var(--text-secondary); font-size: 0.8rem; max-width: 250px; white-space: normal; word-wrap: break-word;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    @forelse($item->files as $file)
                                        <div style="line-height: 1.4;">
                                            {!! preg_replace('~(https?://[^\s<]+)~i', '<a href="$1" target="_blank" style="color: #10b981; text-decoration: underline; word-break: break-all;">$1</a>', e($file->keterangan ?? '-')) !!}
                                        </div>
                                    @empty
                                        <span style="color: var(--text-muted);">-</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Tahun -->
                            <td style="text-align: center; color: var(--text-secondary); font-size: 0.85rem;">
                                {{ $item->tahun }}
                            </td>

                            <!-- Status -->
                            <td style="text-align: center;">
                                @if($item->status === 'valid')
                                    <span class="badge-custom badge-green">Valid</span>
                                @elseif($item->status === 'invalid')
                                    <span class="badge-custom badge-rose">Perlu Perbaikan</span>
                                    @if($item->id_user === auth()->id() && auth()->user()->role === 'kaprodi')
                                        <div style="margin-top: 8px;">
                                            <a href="{{ route('adminprodi.pengisian.edit', $item->id) }}" class="btn btn-rose" style="padding: 4px 10px; font-size: 0.7rem; display: inline-flex; align-items: center; gap: 4px; border-radius: 4px; text-decoration: none;">
                                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                                </svg>
                                                Perbaiki
                                            </a>
                                        </div>
                                    @endif
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
                            <td colspan="8" style="text-align: center; padding: 48px; color: var(--text-muted);">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada pengisian bukti IKU/IKT dari dosen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border); background-color: var(--bg-surface2);">
                {{ $riwayat->appends(['status' => $status, 'tahun' => $tahun])->onEachSide(10)->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
