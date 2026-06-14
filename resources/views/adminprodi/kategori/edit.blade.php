@extends('adminprodi.layouts.app')

@section('title', 'Edit Kategori IKU - Admin Prodi')
@section('page_title', 'Edit Kategori')
@section('page_subtitle', 'Perbarui detail data kategori indikator kinerja utama')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Edit Kategori</h3>

    <form action="{{ route('adminprodi.kategori.update', $kategori->id) }}" method="POST" class="form-layout-container">
        @csrf
        @method('PUT')

        <!-- Nama Kategori -->
        <div class="form-group-custom">
            <label for="nama_kategori" class="form-label-custom">Nama Kategori</label>
            <input type="text" 
                   id="nama_kategori" 
                   name="nama_kategori" 
                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                   placeholder="Contoh: Kurikulum & Pembelajaran" 
                   class="form-input-custom @error('nama_kategori') is-invalid @enderror" 
                   required>
            @error('nama_kategori')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group-custom">
            <label for="deskripsi" class="form-label-custom">Deskripsi Kategori</label>
            <textarea id="deskripsi" 
                      name="deskripsi" 
                      rows="4" 
                      placeholder="Masukkan deskripsi singkat tentang kategori ini..." 
                      class="form-input-custom @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.kategori.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Perbarui Kategori</button>
        </div>
    </form>
</div>
@endsection
