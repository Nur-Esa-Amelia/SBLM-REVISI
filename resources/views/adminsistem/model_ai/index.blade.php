@extends('adminsistem.layouts.app')

@section('title', 'Kelola Model & Token AI - Sistem Early Warning IKU/IKT')
@section('page_title', 'Kelola Model & Token AI')
@section('page_subtitle', 'Konfigurasi integrasi model kecerdasan buatan')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0;">Daftar Konfigurasi Gemini AI</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Atur API Key dan model Gemini AI yang digunakan oleh sistem.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <form action="{{ route('adminsistem.model_ai.activate_all') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); padding: 10px 18px; font-size: 0.85rem; font-weight: 500;" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan semua model?')">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Aktifkan Semua
                    </button>
                </form>
                <form action="{{ route('adminsistem.model_ai.destroy_all') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px 18px; font-size: 0.85rem; font-weight: 500;" onclick="return confirm('Peringatan: Aksi ini akan menghapus semua konfigurasi model Gemini dari database!\n\nApakah Anda benar-benar yakin ingin melanjutkan?')">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Semua
                    </button>
                </form>
                <button type="button" onclick="openAddModal()" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Model
                </button>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Model</th>
                        <th>Model ID</th>
                        <th>API Key/Token</th>
                        <th>Status</th>
                        <th>Tanggal Diperbarui</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $idx => $model)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td style="font-weight: 500;">{{ $model->name }}</td>
                            <td><span style="background-color: var(--bg-surface); padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 0.75rem;">{{ $model->model_id }}</span></td>
                            <td>
                                <span style="background-color: var(--bg-surface); padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 0.75rem; color: var(--text-muted);">
                                    {{ $model->masked_api_key }}
                                </span>
                            </td>
                            <td>
                                @if($model->status === 'aktif')
                                    <span style="display: inline-flex; align-items: center; padding: 4px 10px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 0.75rem; font-weight: 600; border-radius: 20px;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981; margin-right: 6px;"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; padding: 4px 10px; background-color: rgba(107, 114, 128, 0.1); color: #6b7280; font-size: 0.75rem; font-weight: 600; border-radius: 20px;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #6b7280; margin-right: 6px;"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>{{ $model->updated_at->format('d M Y H:i') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    @if($model->status !== 'aktif')
                                        <form action="{{ route('adminsistem.model_ai.activate', $model->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.75rem;" title="Jadikan Aktif">Aktifkan</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn" style="background-color: #f3f4f6; color: #4b5563; padding: 6px 10px;" onclick="openEditModal({{ $model->id }}, '{{ addslashes($model->name) }}', '{{ addslashes($model->model_id) }}', '{{ $model->status }}')" title="Edit Konfigurasi">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('adminsistem.model_ai.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus model {{ addslashes($model->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="background-color: #fee2e2; color: #ef4444; padding: 6px 10px;" title="Hapus">
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
                            <td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                Belum ada konfigurasi model Gemini AI yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Model -->
<div id="addModal" class="custom-modal">
    <div class="custom-modal-content" style="max-width: 500px;">
        <div class="custom-modal-header">
            <h2 class="custom-modal-title">Tambah Model Gemini</h2>
            <button type="button" class="custom-modal-close" onclick="closeAddModal()">&times;</button>
        </div>
        <div class="custom-modal-body">
            <form action="{{ route('adminsistem.model_ai.store') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="form-group-custom">
                        <label for="name" class="form-label-custom">Nama Model</label>
                        <input type="text" id="name" name="name" class="form-input-custom" placeholder="Misal: Gemini 2.5 Flash" required>
                    </div>

                    <div class="form-group-custom">
                        <label for="model_id" class="form-label-custom">Model ID API</label>
                        <input type="text" id="model_id" name="model_id" class="form-input-custom" placeholder="Misal: gemini-2.5-flash" required>
                    </div>

                    <div class="form-group-custom">
                        <label for="api_key" class="form-label-custom">API Key / Token</label>
                        <input type="password" id="api_key" name="api_key" class="form-input-custom" placeholder="AIzaSy..." required>
                    </div>

                    <div class="form-group-custom">
                        <label for="status" class="form-label-custom">Status</label>
                        <select id="status" name="status" class="form-select-custom" required>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="aktif">Aktif</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Model -->
<div id="editModal" class="custom-modal">
    <div class="custom-modal-content" style="max-width: 500px;">
        <div class="custom-modal-header">
            <h2 class="custom-modal-title">Edit Model Gemini</h2>
            <button type="button" class="custom-modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="custom-modal-body">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="form-group-custom">
                        <label for="edit_name" class="form-label-custom">Nama Model</label>
                        <input type="text" id="edit_name" name="name" class="form-input-custom" required>
                    </div>

                    <div class="form-group-custom">
                        <label for="edit_model_id" class="form-label-custom">Model ID API</label>
                        <input type="text" id="edit_model_id" name="model_id" class="form-input-custom" required>
                    </div>

                    <div class="form-group-custom">
                        <label for="edit_api_key" class="form-label-custom">API Key / Token</label>
                        <input type="password" id="edit_api_key" name="api_key" class="form-input-custom" placeholder="Kosongkan jika tidak ingin mengubah API Key">
                    </div>

                    <div class="form-group-custom">
                        <label for="edit_status" class="form-label-custom">Status</label>
                        <select id="edit_status" name="status" class="form-select-custom" required>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="aktif">Aktif</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
        document.getElementById('addModal').style.opacity = '1';
        document.getElementById('addModal').style.visibility = 'visible';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(id, name, modelId, status) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_model_id').value = modelId;
        document.getElementById('edit_status').value = status;
        document.getElementById('editForm').action = '/adminsistem/model-ai/' + id;
        document.getElementById('editModal').style.display = 'flex';
        document.getElementById('editModal').style.opacity = '1';
        document.getElementById('editModal').style.visibility = 'visible';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('addModal')) {
            closeAddModal();
        }
        if (event.target == document.getElementById('editModal')) {
            closeEditModal();
        }
    }
</script>

<style>
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    .custom-modal-content {
        background-color: var(--bg-surface);
        border-radius: 12px;
        width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid var(--border);
    }
    
    .custom-modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .custom-modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    
    .custom-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
        line-height: 1;
        padding: 0;
    }
    
    .custom-modal-close:hover {
        color: var(--text-primary);
    }
    
    .custom-modal-body {
        padding: 24px;
    }
</style>
@endsection
