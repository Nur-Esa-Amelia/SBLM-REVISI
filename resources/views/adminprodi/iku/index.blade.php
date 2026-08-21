@extends('adminprodi.layouts.app')

@section('title', 'Data IKU/IKT - Admin Prodi')
@section('page_title', 'Kelola Data IKU/IKT')
@section('page_subtitle', 'Master data Indikator Kinerja Utama')

@section('content')
<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Daftar Indikator Kinerja Utama</h3>

        <div style="display: flex; align-items: center; gap: 12px; margin-left: auto; flex-wrap: wrap; justify-content: flex-end;">
            <form method="GET" action="{{ route('adminprodi.iku.index') }}" id="filterKategoriForm" style="display: flex; align-items: center; gap: 10px; margin: 0;">
                <select id="id_kategori" name="id_kategori" class="form-select-custom" style="min-width: 180px;" onchange="document.getElementById('filterKategoriForm').submit();">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" {{ (string)$selectedKategori === (string)$kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <div class="search-wrapper">
                    <svg class="search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" class="form-input-custom" placeholder="Cari Kode/Nama IKU/IKT..." value="{{ request('search') }}">
                    <button type="submit" class="btn-search">Cari</button>
                </div>
                @if(request('search') || request('id_kategori'))
                    <a href="{{ route('adminprodi.iku.index') }}" class="btn-reset" title="Reset Pencarian">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>

            @if(auth()->user()->role === 'admin_p2mp')
                <a href="{{ route('adminprodi.iku.create') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.8rem;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Tambah IKU/IKT
                </a>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Kode IKU/IKT</th>
                    <th>Nama IKU/IKT</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    @if(auth()->user()->role === 'admin_p2mp')
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($iku as $index => $item)
                    <tr>
                        <td>{{ $iku->firstItem() + $index }}</td>
                        <td style="font-weight: 600; color: var(--text-primary);">{{ $item->kode_iku ?? '-' }}</td>
                        <td style="font-weight: 600; color: var(--text-primary);">{{ $item->nama_iku }}</td>
                        <td>
                            <span class="badge-custom badge-purple">{{ $item->kategori->nama_kategori }}</span>
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.8rem; max-width: 320px; white-space: normal; overflow-wrap: anywhere; text-align: justify; vertical-align: top; line-height: 1.5;">
                            {{ $item->deskripsi ?? '-' }}
                        </td>
                        @if(auth()->user()->role === 'admin_p2mp')
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="{{ route('adminprodi.iku.edit', $item->id) }}" class="btn-action-edit" title="Edit">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('adminprodi.iku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data IKU/IKT ini? Target tahunan dan pengisian bukti terkait juga akan terhapus.');" style="display: inline-flex;">
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
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin_p2mp' ? 6 : 5 }}" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Belum ada data IKU/IKT.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $iku->onEachSide(10)->links() }}
    </div>
</div>
@endsection
