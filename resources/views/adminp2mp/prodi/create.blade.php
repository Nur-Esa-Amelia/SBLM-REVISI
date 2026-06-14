@extends('adminp2mp.layouts.app')

@section('title', 'Tambah Prodi Baru - Sistem Early Warning IKU')
@section('page_title', 'Tambah Program Studi Baru')
@section('page_subtitle', 'Tambahkan entitas program studi baru ke sistem')

@section('content')
<div class="form-layout-container">
    <!-- Back Link -->
    <div>
        <a href="{{ route('adminp2mp.prodi.index') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all 0.2s ease;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Prodi
        </a>
    </div>

    <!-- Form Card -->
    <div class="card">
        <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #1e293b;">Form Program Studi Baru</h3>

        <form action="{{ route('adminp2mp.prodi.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <!-- Kode Prodi -->
            <div class="form-group-custom">
                <label for="kode_prodi" class="form-label-custom">Kode Program Studi</label>
                <input type="text" name="kode_prodi" id="kode_prodi" value="{{ old('kode_prodi') }}" placeholder="Contoh: TEKINF" required class="form-input-custom" style="text-transform: uppercase;">
                <p style="font-size: 0.65rem; color: #64748b; margin-top: 4px;">Masukkan kode unik prodi (maksimal 20 karakter).</p>
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

