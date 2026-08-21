@extends('adminp2mp.layouts.app')

@section('title', 'Tambah Prodi Baru - Sistem Early Warning IKU/IKT')
@section('page_title', 'Tambah Program Studi Baru')
@section('page_subtitle', 'Tambahkan entitas program studi baru ke sistem')

@section('content')
<div class="form-layout-container">
    <!-- Back Link -->

    <!-- Form Card -->
    <div class="card">
        <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">Form Program Studi Baru</h3>

        <form action="{{ route('adminp2mp.prodi.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <!-- Kode Prodi -->
            <div class="form-group-custom">
                <label for="kode_prodi" class="form-label-custom">Kode Program Studi</label>
                <input type="text" name="kode_prodi" id="kode_prodi" value="{{ old('kode_prodi') }}" placeholder="Contoh: TEKINF" required class="form-input-custom" style="text-transform: uppercase;">
                <p style="font-size: 0.65rem; color: var(--text-muted); margin-top: 4px;">Masukkan kode unik prodi (maksimal 20 karakter).</p>
                @error('kode_prodi')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Prodi -->
            <div class="form-group-custom">
                <label for="nama_prodi" class="form-label-custom">Nama Program Studi</label>
                <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi') }}" placeholder="Contoh: Teknik Informatika" required class="form-input-custom">
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
                    Simpan Prodi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

