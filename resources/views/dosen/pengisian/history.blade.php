@extends('dosen.layouts.app')

@section('title', 'Riwayat Pengisian Bukti - Dosen')
@section('page_title', 'Riwayat Pengisian Bukti')
@section('page_subtitle', 'Pantau status peninjauan dan validasi berkas bukti oleh P2MP')

@section('content')
<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <!-- Filter Year & Upload Action Link -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid #1e293b; padding-bottom: 16px;">
        <form action="{{ route('dosen.pengisian.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; flex: 1;">
            <div class="filter-item-custom" style="max-width: 200px;">
                <label for="tahun" class="form-label-custom">Pilih Tahun Akademik</label>
                <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item-custom" style="max-width: 220px;">
                <label for="status" class="form-label-custom">Pilih Status Validasi</label>
                <select id="status" name="status" class="form-select-custom" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Awaiting Validasi</option>
                    <option value="valid" {{ $status === 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="invalid" {{ $status === 'invalid' ? 'selected' : '' }}>Perlu Perbaikan</option>
                </select>
            </div>
        </form>

        <a href="{{ route('dosen.pengisian.create') }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            Unggah Bukti Baru
        </a>
    </div>

    <!-- Table of submission history -->
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Indikator IKU</th>
                    <th>Jenis Bukti</th>
                    <th>Berkas Lampiran</th>
                    <th>Keterangan</th>
                    <th style="text-align: center;">Tanggal Unggah</th>
                    <th style="text-align: center;">Tanggal Validasi</th>
                    <th style="text-align: center; width: 140px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $index => $item)
                    <tr>
                        <td>{{ $riwayat->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600; color: #ffffff;">{{ $item->iku->nama_iku }}</div>
                            <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                        </td>
                        <td style="font-weight: 500; color: #cbd5e1;">{{ $item->buktiIku->nama_bukti }}</td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                @forelse($item->files as $file)
                                    <a href="{{ asset($file->file_bukti) }}" target="_blank" style="color: #10b981; text-decoration: underline; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $file->nama_file }}
                                    </a>
                                @empty
                                    <span style="color: #64748b; font-size: 0.75rem;">Tidak ada berkas</span>
                                @endforelse
                            </div>
                        </td>
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
                        <td style="text-align: center; color: #cbd5e1; font-size: 0.8rem;">
                            {{ $item->created_at->format('d M Y, H:i') }}
                        </td>
                        <td style="text-align: center; color: #cbd5e1; font-size: 0.8rem;">
                            @if($item->status !== 'pending')
                                {{ $item->updated_at->format('d M Y, H:i') }}
                            @else
                                <span style="color: #64748b; font-style: italic;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($item->status === 'valid')
                                <span class="badge-custom badge-green">Valid</span>
                            @elseif($item->status === 'invalid')
                                <span class="badge-custom badge-rose">Perlu Perbaikan</span>
                                <div style="margin-top: 6px;">
                                    <a href="{{ route('dosen.pengisian.edit', $item->id) }}" class="btn btn-rose" style="padding: 4px 10px; font-size: 0.7rem; display: inline-flex; align-items: center; gap: 4px; border-radius: 4px; text-decoration: none;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                        </svg>
                                        Perbaiki
                                    </a>
                                </div>
                            @else
                                <span class="badge-custom badge-yellow">Awaiting Validasi</span>
                            @endif

                            @if($item->catatan_validator)
                                <div style="font-size: 0.72rem; color: #fb7185; text-align: left; margin-top: 6px; padding: 6px; background-color: rgba(244,63,94,0.06); border-radius: 4px; border: 1px solid rgba(244,63,94,0.1); max-width: 180px; margin-left: auto; margin-right: auto; line-height: 1.3;">
                                    <strong>Catatan:</strong> {{ $item->catatan_validator }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #64748b; padding: 40px;">
                            Belum ada riwayat pengisian bukti IKU untuk tahun akademik {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $riwayat->withQueryString()->links() }}
    </div>
</div>
@endsection
