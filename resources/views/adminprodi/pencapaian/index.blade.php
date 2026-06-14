@extends('adminprodi.layouts.app')

@section('title', 'Target IKU Tahunan - Admin Prodi')
@section('page_title', 'Kelola Target IKU Tahunan')
@section('page_subtitle', 'Tentukan nilai target indikator kinerja utama per tahun akademik')

@section('content')
<!-- Check configuration warning -->
@if(!$settings || $settings->jml_mahasiswa == 0 || $settings->jml_dosen == 0)
<div class="alert-box alert-danger" style="margin-bottom: 20px;">
    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
        <strong>Perhatian!</strong> Jumlah mahasiswa/dosen prodi masih bernilai 0. Silakan isi terlebih dahulu di menu 
        <a href="{{ route('adminprodi.pengaturan.index') }}" style="color: inherit; text-decoration: underline; font-weight: bold;">Pengaturan System</a> 
        agar kalkulasi pencapaian target persentase berjalan dengan benar.
    </div>
</div>
@endif

<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <!-- Filter Year & Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid #1e293b; padding-bottom: 16px;">
        <form action="{{ route('adminprodi.pencapaian.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; flex: 1;">
            <div class="filter-item-custom" style="max-width: 200px;">
                <label for="tahun" class="form-label-custom">Pilih Tahun Akademik</label>
                <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <a href="{{ route('adminprodi.pencapaian.create', ['tahun' => $tahun]) }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            Atur Target Baru
        </a>
    </div>

    <!-- Table content -->
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Indikator IKU</th>
                    <th style="text-align: center;">Tahun</th>
                    <th style="text-align: center;">Target</th>
                    <th style="text-align: center;">Realisasi (Valid)</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 180px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pencapaian as $index => $item)
                    <tr>
                        <td>{{ $pencapaian->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600; color: #ffffff;">{{ $item->iku->nama_iku }}</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                        </td>
                        <td style="text-align: center; font-weight: 600;">{{ $item->tahun }}</td>
                        <td style="text-align: center; font-weight: 700; color: #3b82f6;">
                            {{ $item->target }}{{ $item->satuan === 'persen' ? '%' : '' }}
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: normal;">({{ $item->objek }})</span>
                        </td>
                        <td style="text-align: center; font-weight: 700;">
                            {{ round($item->realisasi) }} Bukti
                        </td>
                        <td style="text-align: center;">
                            @if($item->status === 'Tercapai')
                                <span class="badge-custom badge-green">Tercapai</span>
                            @else
                                <span class="badge-custom badge-rose">Belum Tercapai</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center; align-items: center;">
                                <a href="{{ route('adminprodi.pencapaian.edit', $item->id) }}" class="btn-action-edit" title="Edit">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('adminprodi.pencapaian.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus target IKU ini?');" style="display: inline-flex;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Hapus">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b; padding: 40px;">
                            Belum ada target IKU yang dikonfigurasi untuk tahun akademik {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $pencapaian->appends(['tahun' => $tahun])->links() }}
    </div>
</div>
@endsection
