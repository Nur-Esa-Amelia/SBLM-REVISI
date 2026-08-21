@extends('adminp2mp.layouts.app')

@section('title', 'Edit Prodi - Sistem Early Warning IKU/IKT')
@section('page_title', 'Perbarui Program Studi')
@section('page_subtitle', 'Ubah rincian informasi kode dan nama program studi')

@section('content')
<div class="form-layout-container">


    <!-- Form Card -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">Edit Program Studi</h3>
            <span class="badge-custom badge-blue">ID: #{{ $prodi->id }}</span>
        </div>

        <form action="{{ route('adminp2mp.prodi.update', $prodi->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            @method('PUT')

            <!-- Kode Prodi -->
            <div class="form-group-custom">
                <label for="kode_prodi" class="form-label-custom">Kode Program Studi</label>
                <input type="text" name="kode_prodi" id="kode_prodi" value="{{ old('kode_prodi', $prodi->kode_prodi) }}" placeholder="Contoh: TEKINF" required class="form-input-custom" style="text-transform: uppercase;">
                <p style="font-size: 0.65rem; color: var(--text-muted); margin-top: 4px;">Masukkan kode unik prodi (maksimal 20 karakter).</p>
                @error('kode_prodi')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Prodi -->
            <div class="form-group-custom">
                <label for="nama_prodi" class="form-label-custom">Nama Program Studi</label>
                <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" placeholder="Contoh: Teknik Informatika" required class="form-input-custom">
                @error('nama_prodi')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-footer-actions">
                <a href="{{ route('adminp2mp.prodi.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Perbarui Prodi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

