@extends('adminprodi.layouts.app')

@section('title', 'Penugasan Dosen - Admin Prodi')
@section('page_title', 'Kelola Penugasan Dosen')
@section('page_subtitle', 'Tugaskan dosen program studi untuk mengunggah bukti pemenuhan IKU/IKT')

@section('content')
<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <!-- Filter Year & Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <form action="{{ route('adminprodi.penugasan.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; flex: 1; flex-wrap: wrap;">
            <div class="filter-item-custom" style="max-width: 220px;">
                <label for="tahun" class="form-label-custom">Pilih Tahun Akademik</label>
                <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item-custom" style="max-width: 260px;">
                <label for="id_iku" class="form-label-custom">Filter IKU/IKT</label>
                <select id="id_iku" name="id_iku" class="form-select-custom" onchange="this.form.submit()">
                    <option value="">Semua IKU/IKT</option>
                    @foreach($ikuList as $iku)
                        <option value="{{ $iku->id }}" {{ (string)($selectedIku ?? '') === (string)$iku->id ? 'selected' : '' }}>
                            {{ $iku->nama_iku }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item-custom" style="max-width: 250px;">
                <label for="search" class="form-label-custom">Pencarian</label>
                <div class="search-wrapper">
                    <input type="text" name="search" id="search" class="form-input-custom" placeholder="Cari Dosen/IKU/IKT..." value="{{ request('search') }}">
                    <button type="submit" class="btn-search" title="Cari">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <a href="{{ route('adminprodi.penugasan.index', ['tahun' => $tahun]) }}" class="btn-reset" title="Reset Pencarian">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </form>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">


            <button type="button" onclick="openModal('modalCreate')" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.8rem;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tugaskan Dosen
            </button>
        </div>
    </div>

    <!-- Table content -->
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Dosen Penerima Tugas</th>
                    <th>Indikator IKU/IKT Ditugaskan</th>
                    <th style="text-align: center;">Tahun Akademik</th>
                    <th style="width: 180px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penugasan as $index => $item)
                    <tr>
                        <td>{{ $penugasan->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $item->user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Email: {{ $item->user->email }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #3b82f6; max-width: 350px;">{{ $item->iku->nama_iku }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">Kategori: {{ $item->iku->kategori->nama_kategori }}</div>
                        </td>
                        <td style="text-align: center; font-weight: 600; color: var(--text-secondary);">{{ $item->tahun }}</td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center; align-items: center;">
                                <button type="button" onclick="openEditModal({{ $item->id }}, '{{ $item->id_user }}', '{{ $item->id_iku }}')" class="btn-action-edit" title="Edit">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
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
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            Belum ada penugasan dosen untuk tahun akademik {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $penugasan->appends(['tahun' => $tahun, 'id_iku' => request('id_iku')])->onEachSide(10)->links() }}
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalCreate" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Penugasan Dosen - Tahun {{ $tahun }}</h5>
            <button type="button" class="btn-close" onclick="closeModal('modalCreate')">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('adminprodi.penugasan.store') }}" method="POST" class="ajax-form">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <div class="modal-body">
                <div style="margin-bottom: 16px;">
                    <label for="id_user_create" class="form-label-custom">Pilih Dosen Penerima Tugas <span style="color: #ef4444;">*</span></label>
                    <select id="id_user_create" name="id_user" class="form-select-custom" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosenList as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="id_iku_create" class="form-label-custom">Pilih Indikator IKU/IKT <span style="color: #ef4444;">*</span></label>
                    <select id="id_iku_create" name="id_iku" class="form-select-custom" required>
                        <option value="">-- Pilih Indikator IKU/IKT --</option>
                        @foreach($unassignedIku as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_iku }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-header" style="justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreate')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Penugasan Dosen - Tahun {{ $tahun }}</h5>
            <button type="button" class="btn-close" onclick="closeModal('modalEdit')">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="formEdit" method="POST" class="ajax-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <div class="modal-body">
                <div style="margin-bottom: 16px;">
                    <label for="edit_id_user" class="form-label-custom">Pilih Dosen Penerima Tugas <span style="color: #ef4444;">*</span></label>
                    <select id="edit_id_user" name="id_user" class="form-select-custom" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosenList as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="edit_id_iku" class="form-label-custom">Pilih Indikator IKU/IKT <span style="color: #ef4444;">*</span></label>
                    <select id="edit_id_iku" name="id_iku" class="form-select-custom" required>
                        <option value="">-- Pilih Indikator IKU/IKT --</option>
                        @foreach($ikuList as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_iku }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-header" style="justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, id_user, id_iku) {
        // Set form action
        document.getElementById('formEdit').action = `/adminprodi/penugasan/${id}`;
        
        // Populate inputs
        document.getElementById('edit_id_user').value = id_user;
        document.getElementById('edit_id_iku').value = id_iku;
        
        // Open modal
        openModal('modalEdit');
    }
</script>
@endsection
