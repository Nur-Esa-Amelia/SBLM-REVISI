@extends('adminp2mp.layouts.app')

@section('title', 'Validasi Bukti IKU - Sistem Early Warning IKU')
@section('page_title', 'Validasi Bukti IKU')
@section('page_subtitle', 'Tinjau dan validasi berkas bukti IKU yang diunggah oleh Dosen')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff;">Filter Bukti IKU</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Saring pengajuan bukti IKU berdasarkan program studi, status validasi, atau tahun akademik.</p>
            </div>
        </div>

        <form action="{{ route('adminp2mp.validasi') }}" method="GET" class="filter-row-custom">
            <!-- Prodi Filter -->
            <div class="filter-item-custom">
                <label for="prodi_id" class="form-label-custom">Program Studi</label>
                <select name="prodi_id" id="prodi_id" onchange="this.form.submit()" class="form-select-custom">
                    <option value="">Semua Program Studi</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ $prodiId == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="filter-item-custom">
                <label for="status" class="form-label-custom">Status Validasi</label>
                <select name="status" id="status" onchange="this.form.submit()" class="form-select-custom">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Awaiting Validasi</option>
                    <option value="valid" {{ $status === 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="invalid" {{ $status === 'invalid' ? 'selected' : '' }}>Perlu Perbaikan</option>
                </select>
            </div>

            <!-- Tahun Filter -->
            <div class="filter-item-custom">
                <label for="tahun" class="form-label-custom">Tahun Akademik</label>
                <select name="tahun" id="tahun" onchange="this.form.submit()" class="form-select-custom">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            
            @if($prodiId || $status || $tahun)
                <div style="width: 100%; display: flex; justify-content: flex-end; margin-top: -8px;">
                    <a href="{{ route('adminp2mp.validasi') }}" style="font-size: 0.75rem; font-weight: 600; color: #f43f5e; text-decoration: none;">
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
                        <th style="min-width: 160px;">Dosen / Prodi</th>
                        <th style="min-width: 180px;">Indikator IKU</th>
                        <th style="min-width: 140px;">Jenis Bukti</th>
                        <th style="min-width: 150px;">Berkas Lampiran</th>
                        <th>Keterangan</th>
                        <th style="text-align: center; width: 80px;">Tahun</th>
                        <th style="text-align: center; min-width: 180px;">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $index => $item)
                        <tr>
                            <td>{{ $riwayat->firstItem() + $index }}</td>
                            
                            <!-- Dosen / Prodi -->
                            <td>
                                <div style="font-weight: 700; color: #ffffff;">{{ $item->user->name }}</div>
                                <div style="font-size: 0.72rem; color: #38bdf8; margin-top: 2px;">
                                    {{ $item->user->prodi ? $item->user->prodi->nama_prodi : 'Umum / Tanpa Prodi' }}
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
                                            {!! preg_replace('~(https?://[^\s<]+)~i', '<a href="$1" target="_blank" style="color: #38bdf8; text-decoration: underline; word-break: break-all;">$1</a>', e($file->keterangan ?? '-')) !!}
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

                            <!-- Status / Aksi -->
                            <td style="text-align: center;">
                                <!-- Current Status / Ubah validation toggle -->
                                <div id="status-display-{{ $item->id }}" style="{{ $item->status === 'pending' ? 'display: none;' : '' }}">
                                    @if($item->status === 'valid')
                                        <span class="badge-custom badge-green">Valid</span>
                                    @elseif($item->status === 'invalid')
                                        <span class="badge-custom badge-rose">Perlu Perbaikan</span>
                                    @else
                                        <span class="badge-custom badge-yellow">Awaiting Validasi</span>
                                    @endif
                                    
                                    @if($item->catatan_validator)
                                        <div style="font-size: 0.72rem; color: #fb7185; text-align: left; margin-top: 6px; padding: 6px; background-color: rgba(244,63,94,0.06); border-radius: 4px; border: 1px solid rgba(244,63,94,0.1); max-width: 200px; margin-left: auto; margin-right: auto; line-height: 1.3;">
                                            <strong>Catatan:</strong> {{ $item->catatan_validator }}
                                        </div>
                                    @endif

                                    @if($item->status !== 'pending')
                                        <div style="margin-top: 8px;">
                                            <button type="button" onclick="toggleValidationForm({{ $item->id }})" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.7rem; border-radius: 6px; width: 100%; justify-content: center;">
                                                Ubah Status
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Forms -->
                                <div id="status-form-{{ $item->id }}" style="{{ $item->status === 'pending' ? '' : 'display: none;' }}">
                                    <!-- Approve Action -->
                                    <form action="{{ route('adminp2mp.validasi.update', $item->id) }}" method="POST" style="display: block; margin-bottom: 6px; width: 100%;">
                                        @csrf
                                        <input type="hidden" name="status" value="valid">
                                        <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.75rem; width: 100%; justify-content: center; background-color: #10b981; border-color: #10b981;">
                                            Setujui (Valid)
                                        </button>
                                    </form>

                                    <!-- Reject Toggle -->
                                    <button type="button" onclick="showRejectInput({{ $item->id }})" id="btn-reject-trigger-{{ $item->id }}" class="btn btn-rose" style="padding: 6px 12px; font-size: 0.75rem; width: 100%; justify-content: center;">
                                        Minta Perbaikan
                                    </button>

                                    <!-- Reject Form -->
                                    <form id="form-reject-{{ $item->id }}" action="{{ route('adminp2mp.validasi.update', $item->id) }}" method="POST" style="display: none; margin-top: 8px;">
                                        @csrf
                                        <input type="hidden" name="status" value="invalid">
                                        <div style="display: flex; flex-direction: column; gap: 6px; text-align: left;">
                                            <label for="catatan-{{ $item->id }}" class="form-label-custom" style="font-size: 0.7rem; color: #94a3b8;">Catatan Perbaikan</label>
                                            <textarea name="catatan_validator" id="catatan-{{ $item->id }}" class="form-input-custom" style="padding: 6px 8px; font-size: 0.75rem; height: 60px; resize: vertical; background-color: #090d16;" placeholder="Alasan penolakan / revisi..." required>{{ $item->catatan_validator }}</textarea>
                                            <div style="display: flex; gap: 6px;">
                                                <button type="submit" class="btn btn-rose" style="padding: 4px 8px; font-size: 0.7rem; flex: 1; justify-content: center;">
                                                    Kirim
                                                </button>
                                                <button type="button" onclick="hideRejectInput({{ $item->id }})" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.7rem; flex: 1; justify-content: center;">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Cancel Edit for previously validated entry -->
                                    @if($item->status !== 'pending')
                                        <button type="button" onclick="toggleValidationForm({{ $item->id }})" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; width: 100%; justify-content: center; margin-top: 6px;">
                                            Batal Ubah
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px; color: #64748b;">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada pengajuan bukti IKU yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #1e293b; background-color: rgba(15, 23, 42, 0.2);">
                {{ $riwayat->appends(['prodi_id' => $prodiId, 'status' => $status, 'tahun' => $tahun])->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function toggleValidationForm(id) {
        const form = document.getElementById('status-form-' + id);
        const display = document.getElementById('status-display-' + id);
        if (form.style.display === 'none') {
            form.style.display = 'block';
            display.style.display = 'none';
        } else {
            form.style.display = 'none';
            display.style.display = 'block';
            hideRejectInput(id); // reset form penolakan
        }
    }

    function showRejectInput(id) {
        document.getElementById('form-reject-' + id).style.display = 'block';
        document.getElementById('btn-reject-trigger-' + id).style.display = 'none';
    }

    function hideRejectInput(id) {
        document.getElementById('form-reject-' + id).style.display = 'none';
        document.getElementById('btn-reject-trigger-' + id).style.display = 'block';
    }
</script>
@endsection

