@extends('adminp2mp.layouts.app')

@section('title', 'Kelola Program Studi - Sistem Early Warning IKU')
@section('page_title', 'Kelola Program Studi')
@section('page_subtitle', 'Kelola data program studi aktif dalam sistem early warning IKU')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Action Header & Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff;">Daftar Program Studi</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Kelola data program studi aktif dalam sistem early warning IKU.</p>
            </div>
            <div>
                <a href="{{ route('adminp2mp.prodi.create') }}" class="btn btn-primary">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Prodi Baru
                </a>
            </div>
        </div>

        <!-- Filters Form -->
        <form action="{{ route('adminp2mp.prodi.index') }}" method="GET" class="filter-row-custom">
            <!-- Search -->
            <div class="filter-item-custom" style="flex: 2;">
                <label for="search" class="form-label-custom">Cari Program Studi</label>
                <div class="filter-input-search-wrapper">
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama Prodi / Kode Prodi..." class="form-input-custom filter-input-search">
                    <div class="filter-input-search-icon">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn btn-secondary" style="height: 38px; min-width: 100px;">
                    Cari
                </button>
            </div>
            
            @if($search)
                <div style="width: 100%; display: flex; justify-content: flex-end; margin-top: -8px;">
                    <a href="{{ route('adminp2mp.prodi.index') }}" style="font-size: 0.75rem; font-weight: 600; color: #f43f5e; text-decoration: none;">
                        Hapus Pencarian
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
                        <th>Kode Prodi</th>
                        <th>Nama Program Studi</th>
                        <th>Jumlah Pengguna</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prodis as $prodi)
                        <tr>
                            <!-- Kode Prodi -->
                            <td>
                                <span class="badge-custom badge-blue">
                                    {{ $prodi->kode_prodi }}
                                </span>
                            </td>
                            <!-- Nama Prodi -->
                            <td style="font-weight: 700; color: #ffffff;">
                                {{ $prodi->nama_prodi }}
                            </td>
                            <!-- Users Count -->
                            <td style="font-weight: 600; color: #cbd5e1;">
                                {{ $prodi->users_count }} User
                            </td>
                            <!-- Actions -->
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 8px; justify-content: flex-end;">
                                    <!-- Edit Link -->
                                    <a href="{{ route('adminp2mp.prodi.edit', $prodi->id) }}" class="btn-action-edit" title="Edit Prodi">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('adminp2mp.prodi.destroy', $prodi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus prodi ini? Seluruh user di bawah prodi ini akan dilepas asosiasinya.')" style="display: inline-flex;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-delete" title="Hapus Prodi">
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
                            <td colspan="4" style="text-align: center; padding: 48px; color: #64748b;">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tidak ditemukan data program studi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($prodis->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #1e293b; background-color: rgba(15, 23, 42, 0.2);">
                {{ $prodis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

