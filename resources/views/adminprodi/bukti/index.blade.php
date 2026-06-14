@extends('adminprodi.layouts.app')

@section('title', 'Jenis Bukti - Admin Prodi')
@section('page_title', 'Kelola Jenis Bukti')
@section('page_subtitle', 'Tentukan jenis berkas/dokumen bukti yang wajib diunggah untuk setiap IKU')

@section('content')
<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 700;">Daftar Jenis Bukti IKU</h3>
        @if(auth()->user()->role === 'admin_p2mp')
            <a href="{{ route('adminprodi.bukti.create') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.8rem;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Jenis Bukti
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Bukti</th>
                    <th>Terkait IKU</th>
                    <th>Keterangan / Deskripsi</th>
                    @if(auth()->user()->role === 'admin_p2mp')
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($bukti as $index => $item)
                    <tr>
                        <td>{{ $bukti->firstItem() + $index }}</td>
                        <td style="font-weight: 600; color: #ffffff;">{{ $item->nama_bukti }}</td>
                        <td>
                            <div style="font-weight: 600; color: #3b82f6; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $item->iku->nama_iku }}
                            </div>
                        </td>
                        <td style="color: #cbd5e1; font-size: 0.8rem; max-width: 300px; line-height: 1.4;">
                            {{ $item->deskripsi ?? '-' }}
                        </td>
                        @if(auth()->user()->role === 'admin_p2mp')
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="{{ route('adminprodi.bukti.edit', $item->id) }}" class="btn-action-edit" title="Edit">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('adminprodi.bukti.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis bukti ini? Seluruh file bukti yang telah diupload dosen untuk tipe bukti ini juga akan ikut terhapus.');" style="display: inline-flex;">
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
                        <td colspan="{{ auth()->user()->role === 'admin_p2mp' ? 5 : 4 }}" style="text-align: center; color: #64748b; padding: 30px;">
                            Belum ada data jenis bukti.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $bukti->links() }}
    </div>
</div>
@endsection
