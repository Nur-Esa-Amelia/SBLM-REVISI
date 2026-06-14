@extends('adminprodi.layouts.app')

@section('title', 'Penugasan Dosen - Admin Prodi')
@section('page_title', 'Kelola Penugasan Dosen')
@section('page_subtitle', 'Tugaskan dosen program studi untuk mengunggah bukti pemenuhan IKU')

@section('content')
<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <!-- Filter Year & Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid #1e293b; padding-bottom: 16px;">
        <form action="{{ route('adminprodi.penugasan.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; flex: 1;">
            <div class="filter-item-custom" style="max-width: 200px;">
                <label for="tahun" class="form-label-custom">Pilih Tahun Akademik</label>
                <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <a href="{{ route('adminprodi.penugasan.create', ['tahun' => $tahun]) }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            Tugaskan Dosen
        </a>
    </div>

    <!-- Table content -->
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Dosen Penerima Tugas</th>
                    <th>Indikator IKU Ditugaskan</th>
                    <th style="text-align: center;">Tahun Akademik</th>
                    <th style="width: 180px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penugasan as $index => $item)
                    <tr>
                        <td>{{ $penugasan->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600; color: #ffffff;">{{ $item->user->name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Email: {{ $item->user->email }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #3b82f6; max-width: 350px;">{{ $item->iku->nama_iku }}</div>
                            <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                        </td>
                        <td style="text-align: center; font-weight: 600; color: #cbd5e1;">{{ $item->tahun }}</td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center; align-items: center;">
                                <a href="{{ route('adminprodi.penugasan.edit', $item->id) }}" class="btn-action-edit" title="Edit">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('adminprodi.penugasan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan dosen ini?');" style="display: inline-flex;">
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
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 40px;">
                            Belum ada penugasan dosen untuk tahun akademik {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $penugasan->appends(['tahun' => $tahun])->links() }}
    </div>
</div>
@endsection
